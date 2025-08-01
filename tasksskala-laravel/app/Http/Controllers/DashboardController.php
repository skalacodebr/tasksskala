<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Models\Projeto;
use App\Models\Colaborador;
use App\Models\Cliente;
use App\Models\MarcosProjeto;
use App\Models\Tutorial;
use App\Models\TarefaTransferencia;
use App\Services\GoogleCalendarService;
use App\Services\OpenAIService;
use App\Traits\WhatsAppNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    use WhatsAppNotification;
    
    private GoogleCalendarService $googleCalendarService;
    private OpenAIService $openAIService;
    
    public function __construct(GoogleCalendarService $googleCalendarService, OpenAIService $openAIService)
    {
        $this->googleCalendarService = $googleCalendarService;
        $this->openAIService = $openAIService;
    }
    
    public function index()
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        // Estatísticas das tarefas
        $tarefasPendentes = Tarefa::where('colaborador_id', $colaborador->id)
            ->where('status', 'pendente')
            ->count();

        $tarefasEmAndamento = Tarefa::where('colaborador_id', $colaborador->id)
            ->where('status', 'em_andamento')
            ->count();

        $tarefasConcluidas = Tarefa::where('colaborador_id', $colaborador->id)
            ->where('status', 'concluida')
            ->whereDate('updated_at', Carbon::today())
            ->count();

        $tarefasAtrasadas = Tarefa::where('colaborador_id', $colaborador->id)
            ->where('data_vencimento', '<', Carbon::now())
            ->whereIn('status', ['pendente', 'em_andamento'])
            ->count();

        // Tarefas recentes (últimas 10)
        $tarefasRecentes = Tarefa::where('colaborador_id', $colaborador->id)
            ->with(['projeto'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Tarefas por prioridade
        $tarefasPrioridade = Tarefa::where('colaborador_id', $colaborador->id)
            ->whereIn('status', ['pendente', 'em_andamento'])
            ->selectRaw('prioridade, COUNT(*) as total')
            ->groupBy('prioridade')
            ->pluck('total', 'prioridade')
            ->toArray();

        // Próximas tarefas com vencimento
        $proximasTarefas = Tarefa::where('colaborador_id', $colaborador->id)
            ->whereNotNull('data_vencimento')
            ->where('data_vencimento', '>', Carbon::now())
            ->whereIn('status', ['pendente', 'em_andamento'])
            ->with(['projeto'])
            ->orderBy('data_vencimento', 'asc')
            ->limit(5)
            ->get();

        // Buscar eventos do Google Calendar
        $googleEvents = [];
        $isGoogleConnected = false;
        
        if ($colaborador->googleOAuthToken) {
            $isGoogleConnected = true;
            try {
                $googleEvents = $this->googleCalendarService->getUpcomingEvents($colaborador, 10);
            } catch (\Exception $e) {
                $googleEvents = [];
            }
        }

        return view('dashboard', compact(
            'colaborador',
            'tarefasPendentes',
            'tarefasEmAndamento', 
            'tarefasConcluidas',
            'tarefasAtrasadas',
            'tarefasRecentes',
            'tarefasPrioridade',
            'proximasTarefas',
            'googleEvents',
            'isGoogleConnected'
        ));
    }

    public function minhasTarefas(Request $request)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        $query = Tarefa::where('colaborador_id', $colaborador->id)->with(['projeto']);

        // Filtros
        if ($request->filled('status')) {
            if ($request->status == 'pendente_em_andamento') {
                $query->whereIn('status', ['pendente', 'em_andamento']);
            } else {
                $query->where('status', $request->status);
            }
        } elseif (!$request->hasAny(['prioridade', 'projeto_id'])) {
            // Se nenhum filtro foi aplicado, mostrar apenas pendentes e em andamento por padrão
            $query->whereIn('status', ['pendente', 'em_andamento']);
        }

        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }

        if ($request->filled('projeto_id')) {
            $query->where('projeto_id', $request->projeto_id);
        }

        $tarefas = $query->orderBy('data_vencimento', 'asc')
                        ->orderBy('prioridade', 'desc')
                        ->paginate(15);

        // Para os filtros
        $projetos = Projeto::whereHas('tarefas', function($q) use ($colaborador) {
            $q->where('colaborador_id', $colaborador->id);
        })->get();

        return view('minhas-tarefas', compact('tarefas', 'projetos', 'colaborador'));
    }

    public function iniciarTarefa(Tarefa $tarefa)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador || $tarefa->colaborador_id != $colaborador->id) {
            return redirect('/dashboard')->with('error', 'Acesso negado!');
        }

        if ($tarefa->status !== 'pendente') {
            return redirect()->back()->with('error', 'Tarefa não pode ser iniciada!');
        }

        $tarefa->iniciarTarefa();

        return redirect()->back()->with('success', 'Tarefa iniciada com sucesso!');
    }

    public function concluirTarefa(Request $request, Tarefa $tarefa)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador || $tarefa->colaborador_id != $colaborador->id) {
            return redirect('/dashboard')->with('error', 'Acesso negado!');
        }

        if ($tarefa->status !== 'em_andamento') {
            return redirect()->back()->with('error', 'Tarefa não está em andamento!');
        }

        $validated = $request->validate([
            'observacoes' => 'nullable|string|max:1000'
        ]);

        $tarefa->concluirTarefa($validated['observacoes'] ?? null);
        
        // Criar tarefa de teste se necessário
        if ($tarefa->criar_tarefa_teste && $tarefa->testador_id) {
            $this->criarTarefaTeste($tarefa);
        }

        return redirect()->back()->with('success', 'Tarefa concluída com sucesso!');
    }

    public function verTarefa(Tarefa $tarefa)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador || $tarefa->colaborador_id != $colaborador->id) {
            return redirect('/dashboard')->with('error', 'Acesso negado!');
        }

        $tarefa->load(['projeto']);
        
        return view('tarefa-detalhes', compact('tarefa', 'colaborador'));
    }

    public function criarTarefa()
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        // Buscar todos os colaboradores para poder atribuir tarefas
        $colaboradores = Colaborador::orderBy('nome')->get();
        
        // Buscar projetos para poder vincular (opcional)
        $projetos = Projeto::orderBy('nome')->get();

        return view('criar-tarefa', compact('colaborador', 'colaboradores', 'projetos'));
    }

    public function armazenarTarefa(Request $request)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        // Verificar se é criação múltipla
        if ($request->input('multiplas_tarefas')) {
            // Validação para múltiplas tarefas
            $validated = $request->validate([
                'titulo_base' => 'nullable|string|max:255',
                'descricoes' => 'required|array|min:1',
                'descricoes.*' => 'required|string|max:2000',
                'prazos' => 'nullable|array',
                'prazos.*' => 'nullable|date',
                'colaborador_id' => 'required|exists:colaboradores,id',
                'projeto_id' => 'nullable|exists:projetos,id',
                'prioridade' => 'required|in:baixa,media,alta,urgente',
            ]);

            $tarefasCriadas = 0;
            
            foreach ($validated['descricoes'] as $index => $descricao) {
                // Extrair título da descrição ou usar título base
                if (!empty($validated['titulo_base'])) {
                    $titulo = $validated['titulo_base'] . ' - ' . Str::limit($descricao, 50, '');
                } else {
                    // Pegar primeiros 60 caracteres da descrição como título
                    $titulo = Str::limit($descricao, 60, '...');
                }

                $tarefaData = [
                    'titulo' => $titulo,
                    'descricao' => $descricao,
                    'colaborador_id' => $validated['colaborador_id'],
                    'projeto_id' => $validated['projeto_id'],
                    'prioridade' => $validated['prioridade'],
                    'data_vencimento' => $validated['prazos'][$index] ?? null,
                    'tipo' => 'manual',
                    'status' => 'pendente',
                    'created_by' => $colaborador->id,
                ];

                $tarefa = Tarefa::create($tarefaData);
                
                // Enviar notificação WhatsApp se a tarefa é para outro colaborador
                if ($validated['colaborador_id'] != $colaborador->id) {
                    $colaboradorDestino = Colaborador::find($validated['colaborador_id']);
                    if ($colaboradorDestino && $colaboradorDestino->whatsapp) {
                        $mensagem = "🔔 *Nova Tarefa Atribuída*\n\n";
                        $mensagem .= "Olá {$colaboradorDestino->nome}!\n\n";
                        $mensagem .= "{$colaborador->nome} adicionou uma nova tarefa para você:\n\n";
                        $mensagem .= "📋 *Título:* {$tarefa->titulo}\n";
                        $mensagem .= "🎯 *Prioridade:* " . ucfirst($tarefa->prioridade) . "\n";
                        if ($tarefa->data_vencimento) {
                            $mensagem .= "📅 *Prazo:* " . Carbon::parse($tarefa->data_vencimento)->format('d/m/Y') . "\n";
                        }
                        $mensagem .= "\n💻 Acesse o intranet para mais detalhes.";
                        
                        $this->enviarNotificacaoWhatsApp($colaboradorDestino->whatsapp, $mensagem);
                    }
                }
                
                $tarefasCriadas++;
            }

            return redirect('/minhas-tarefas')->with('success', $tarefasCriadas . ' tarefas criadas com sucesso!');
        } else {
            // Validação para tarefa única (código existente)
            $validated = $request->validate([
                'titulo' => 'required|string|max:255',
                'descricao' => 'nullable|string|max:2000',
                'colaborador_id' => 'required|exists:colaboradores,id',
                'projeto_id' => 'nullable|exists:projetos,id',
                'prioridade' => 'required|in:baixa,media,alta,urgente',
                'data_vencimento' => 'nullable|date|after:now',
                'recorrente' => 'boolean',
                'frequencia_recorrencia' => 'nullable|in:diaria,semanal,mensal|required_if:recorrente,1',
                'criar_tarefa_teste' => 'boolean',
                'testador_id' => 'nullable|exists:colaboradores,id|required_if:criar_tarefa_teste,1',
            ]);

            $validated['tipo'] = 'manual';
            $validated['status'] = 'pendente';
            $validated['created_by'] = $colaborador->id;

            $tarefa = Tarefa::create($validated);
            
            // Enviar notificação WhatsApp se a tarefa é para outro colaborador
            if ($validated['colaborador_id'] != $colaborador->id) {
                $colaboradorDestino = Colaborador::find($validated['colaborador_id']);
                if ($colaboradorDestino && $colaboradorDestino->whatsapp) {
                    $mensagem = "🔔 *Nova Tarefa Atribuída*\n\n";
                    $mensagem .= "Olá {$colaboradorDestino->nome}!\n\n";
                    $mensagem .= "{$colaborador->nome} adicionou uma nova tarefa para você:\n\n";
                    $mensagem .= "📋 *Título:* {$tarefa->titulo}\n";
                    $mensagem .= "🎯 *Prioridade:* " . ucfirst($tarefa->prioridade) . "\n";
                    if ($tarefa->data_vencimento) {
                        $mensagem .= "📅 *Prazo:* " . Carbon::parse($tarefa->data_vencimento)->format('d/m/Y') . "\n";
                    }
                    $mensagem .= "\n💻 Acesse o intranet para mais detalhes.";
                    
                    $this->enviarNotificacaoWhatsApp($colaboradorDestino->whatsapp, $mensagem);
                }
            }

            return redirect('/minhas-tarefas')->with('success', 'Tarefa criada com sucesso!');
        }
    }

    public function testarApiKey()
    {
        try {
            $apiKey = config('services.openai.api_key');
            
            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'API Key não configurada no arquivo .env'
                ]);
            }
            
            // Teste simples com a API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->get('https://api.openai.com/v1/models');
            
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'API Key válida e funcionando!',
                    'modelos_disponiveis' => count($response->json()['data'] ?? [])
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'API Key inválida ou erro na API',
                    'status' => $response->status(),
                    'erro' => $response->json()
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao testar API Key: ' . $e->getMessage()
            ]);
        }
    }
    
    public function processarTarefaIA(Request $request)
    {
        \Log::info('ProcessarTarefaIA iniciado', ['tipo' => $request->input('tipo')]);
        
        // Verificar API Key primeiro
        $apiKey = config('services.openai.api_key');
        if (!$apiKey) {
            \Log::error('ProcessarTarefaIA: API Key da OpenAI não configurada');
            return response()->json([
                'success' => false, 
                'message' => 'API Key da OpenAI não configurada. Configure OPENAI_API_KEY no arquivo .env'
            ], 500);
        }
        
        // Tentar obter colaborador da sessão
        $colaboradorId = session('colaborador_id');
        \Log::info('ProcessarTarefaIA: ID do colaborador na sessão', ['id' => $colaboradorId]);
        
        $colaborador = null;
        if ($colaboradorId) {
            $colaborador = Colaborador::find($colaboradorId);
            \Log::info('ProcessarTarefaIA: Colaborador encontrado', ['colaborador' => $colaborador ? $colaborador->nome : 'não encontrado']);
        }
        
        if (!$colaborador) {
            // Tentar obter diretamente da sessão (método antigo)
            $colaborador = session('colaborador');
            \Log::info('ProcessarTarefaIA: Tentando método antigo de sessão', ['colaborador' => $colaborador ? 'encontrado' : 'não encontrado']);
        }
        
        if (!$colaborador) {
            \Log::error('ProcessarTarefaIA: Colaborador não autenticado');
            return response()->json(['success' => false, 'message' => 'Não autorizado'], 401);
        }

        $tipo = $request->input('tipo');
        $conteudo = '';

        try {
            if ($tipo === 'audio') {
                \Log::info('ProcessarTarefaIA: Processando áudio');
                
                // Processar áudio
                if (!$request->hasFile('audio')) {
                    \Log::error('ProcessarTarefaIA: Nenhum arquivo de áudio enviado');
                    return response()->json(['success' => false, 'message' => 'Nenhum arquivo de áudio enviado'], 400);
                }

                $audioFile = $request->file('audio');
                \Log::info('ProcessarTarefaIA: Arquivo de áudio recebido', [
                    'nome' => $audioFile->getClientOriginalName(),
                    'tamanho' => $audioFile->getSize(),
                    'mime' => $audioFile->getMimeType()
                ]);
                
                $audioPath = $audioFile->store('temp-audio');
                \Log::info('ProcessarTarefaIA: Áudio salvo temporariamente', ['path' => $audioPath]);
                
                // Usar OpenAI Whisper para transcrever
                $apiKey = config('services.openai.api_key');
                
                if (!$apiKey) {
                    \Log::error('ProcessarTarefaIA: API Key da OpenAI não configurada');
                    Storage::delete($audioPath);
                    return response()->json(['success' => false, 'message' => 'API Key da OpenAI não configurada'], 500);
                }
                
                \Log::info('ProcessarTarefaIA: Usando API Key', ['key_length' => strlen($apiKey), 'key_prefix' => substr($apiKey, 0, 10) . '...']);
                
                try {
                    \Log::info('ProcessarTarefaIA: Enviando áudio para Whisper API');
                    
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                    ])->attach(
                        'file', 
                        file_get_contents(storage_path('app/' . $audioPath)), 
                        'audio.wav'
                    )->post('https://api.openai.com/v1/audio/transcriptions', [
                        'model' => 'whisper-1',
                        'language' => 'pt'
                    ]);
                    
                    \Log::info('ProcessarTarefaIA: Resposta do Whisper', [
                        'status' => $response->status(),
                        'success' => $response->successful()
                    ]);
                } catch (\Exception $e) {
                    \Log::error('ProcessarTarefaIA: Erro ao chamar Whisper API', [
                        'erro' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    Storage::delete($audioPath);
                    throw $e;
                }

                if ($response->successful()) {
                    $conteudo = $response->json()['text'];
                    \Log::info('ProcessarTarefaIA: Áudio transcrito com sucesso', ['texto' => substr($conteudo, 0, 100) . '...']);
                    // Limpar arquivo temporário
                    Storage::delete($audioPath);
                } else {
                    \Log::error('ProcessarTarefaIA: Erro na resposta do Whisper', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    Storage::delete($audioPath);
                    return response()->json(['success' => false, 'message' => 'Erro ao transcrever áudio: ' . $response->body()], 500);
                }
            } else {
                // Texto direto
                $conteudo = $request->input('conteudo');
                \Log::info('ProcessarTarefaIA: Processando texto', ['tamanho' => strlen($conteudo)]);
            }

            // Buscar todos os colaboradores e projetos para facilitar a identificação
            $todosColaboradores = Colaborador::select('id', 'nome')->get();
            $todosProjetos = Projeto::select('id', 'nome')->get();
            
            // Criar lista de colaboradores para o prompt
            $listaColaboradores = $todosColaboradores->map(function($colab) {
                return "ID: {$colab->id}, Nome: {$colab->nome}";
            })->join("\n");
            
            // Criar lista de projetos para o prompt
            $listaProjetos = $todosProjetos->map(function($proj) {
                return "ID: {$proj->id}, Nome: {$proj->nome}";
            })->join("\n");

            // Processar conteúdo com ChatGPT
            $prompt = "Você é um assistente especializado em extrair e organizar tarefas a partir de descrições em linguagem natural.

COLABORADORES DISPONÍVEIS:
$listaColaboradores

PROJETOS DISPONÍVEIS:
$listaProjetos

Analise o seguinte texto e extraia as tarefas mencionadas:
\"$conteudo\"

Para cada tarefa identificada, retorne um JSON com a seguinte estrutura:
{
    \"tarefas\": [
        {
            \"titulo\": \"título curto e descritivo\",
            \"descricao\": \"descrição detalhada da tarefa\",
            \"prazo\": \"YYYY-MM-DD (se mencionado, caso contrário null)\",
            \"prioridade\": \"baixa|media|alta|urgente (baseado no contexto, padrão: media)\",
            \"colaborador_nome\": \"nome do colaborador se mencionado\",
            \"projeto_nome\": \"nome do projeto se mencionado\",
            \"titulo_base\": \"título base se múltiplas tarefas similares\"
        }
    ]
}

Regras importantes:
1. Se múltiplas tarefas forem mencionadas, crie uma entrada para cada uma
2. Interprete prazos relativos (hoje, amanhã, próxima semana, sexta-feira, etc) baseado na data atual: " . now()->toDateString() . " e dia da semana: " . now()->locale('pt')->dayName . "
3. IDENTIFICAÇÃO DE COLABORADOR: 
   - Procure por nomes mencionados no texto e compare com a lista de COLABORADORES DISPONÍVEIS acima
   - Use correspondência parcial e variações (João = Joao, Maria = MARIA, etc)
   - Procure por palavras como 'para', 'responsável', 'atribuir para', 'designar para' seguidas de nomes
   - SEMPRE retorne o nome EXATO como está na lista de colaboradores disponíveis
4. IDENTIFICAÇÃO DE PROJETO:
   - Procure por nomes de projetos mencionados e compare com a lista de PROJETOS DISPONÍVEIS acima
   - Use correspondência parcial (se falar \"vendas\" e existir \"Sistema de Vendas\", use este)
   - Procure por palavras como 'projeto', 'sistema', 'app', 'site' seguidas de nomes
   - SEMPRE retorne o nome EXATO como está na lista de projetos disponíveis
5. Determine a prioridade baseado em palavras como: urgente, importante, crítico, ASAP (alta/urgente), normal, padrão (media), quando der, depois (baixa)
6. Se as tarefas parecem relacionadas ou foram mencionadas juntas, sugira um titulo_base comum
7. Se não conseguir identificar um colaborador da lista, deixe colaborador_nome como null
8. Se não conseguir identificar um projeto da lista, deixe projeto_nome como null
9. Cada tarefa deve ter seu próprio título e descrição completa
10. Separe tarefas diferentes mesmo que estejam relacionadas ao mesmo projeto

Exemplos de extração:
- Se na lista tem "João Silva" e o texto diz "para o João", use colaborador_nome: "João Silva"
- Se na lista tem "Sistema de Vendas" e o texto diz "projeto vendas", use projeto_nome: "Sistema de Vendas"
- "Maria precisa revisar o código" → procure "Maria" na lista de colaboradores e use o nome completo encontrado

Retorne APENAS o JSON, sem explicações adicionais.";

            \Log::info('ProcessarTarefaIA: Enviando para ChatGPT');
            
            try {
                $apiKey = config('services.openai.api_key');
                
                if (!$apiKey) {
                    \Log::error('ProcessarTarefaIA: API Key da OpenAI não configurada para ChatGPT');
                    return response()->json(['success' => false, 'message' => 'API Key da OpenAI não configurada'], 500);
                }
                
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => $prompt],
                        ['role' => 'user', 'content' => $conteudo]
                    ],
                    'temperature' => 0.3,
                    'response_format' => ['type' => 'json_object']
                ]);
                
                \Log::info('ProcessarTarefaIA: Resposta do ChatGPT', [
                    'status' => $response->status(),
                    'success' => $response->successful()
                ]);
            } catch (\Exception $e) {
                \Log::error('ProcessarTarefaIA: Erro ao chamar ChatGPT API', [
                    'erro' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            if ($response->successful()) {
                \Log::info('ProcessarTarefaIA: ChatGPT respondeu com sucesso');
                
                $responseContent = $response->json()['choices'][0]['message']['content'];
                \Log::info('ProcessarTarefaIA: Conteúdo da resposta', ['content' => $responseContent]);
                
                $resultado = json_decode($responseContent, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('ProcessarTarefaIA: Erro ao decodificar JSON', [
                        'erro' => json_last_error_msg(),
                        'content' => $responseContent
                    ]);
                    return response()->json(['success' => false, 'message' => 'Erro ao processar resposta da IA'], 500);
                }
                
                // Processar tarefas e tentar encontrar IDs
                $tarefasProcessadas = [];
                \Log::info('ProcessarTarefaIA: Processando tarefas', ['total' => count($resultado['tarefas'] ?? [])]);
                
                foreach ($resultado['tarefas'] as $tarefa) {
                    $tarefaProcessada = $tarefa;
                    
                    // Tentar encontrar colaborador por nome
                    if (!empty($tarefa['colaborador_nome'])) {
                        \Log::info('ProcessarTarefaIA: Buscando colaborador', ['nome' => $tarefa['colaborador_nome']]);
                        $colaboradorEncontrado = Colaborador::where('nome', 'like', '%' . $tarefa['colaborador_nome'] . '%')->first();
                        if ($colaboradorEncontrado) {
                            $tarefaProcessada['colaborador_id'] = $colaboradorEncontrado->id;
                            \Log::info('ProcessarTarefaIA: Colaborador encontrado', ['id' => $colaboradorEncontrado->id]);
                        } else {
                            \Log::warning('ProcessarTarefaIA: Colaborador não encontrado', ['nome' => $tarefa['colaborador_nome']]);
                        }
                    }
                    
                    // Se não encontrou colaborador, usar o atual
                    if (empty($tarefaProcessada['colaborador_id'])) {
                        $tarefaProcessada['colaborador_id'] = $colaborador->id;
                    }
                    
                    // Tentar encontrar projeto por nome
                    if (!empty($tarefa['projeto_nome'])) {
                        \Log::info('ProcessarTarefaIA: Buscando projeto', ['nome' => $tarefa['projeto_nome']]);
                        $projetoEncontrado = Projeto::where('nome', 'like', '%' . $tarefa['projeto_nome'] . '%')->first();
                        if ($projetoEncontrado) {
                            $tarefaProcessada['projeto_id'] = $projetoEncontrado->id;
                            \Log::info('ProcessarTarefaIA: Projeto encontrado', ['id' => $projetoEncontrado->id]);
                        } else {
                            \Log::warning('ProcessarTarefaIA: Projeto não encontrado', ['nome' => $tarefa['projeto_nome']]);
                        }
                    }
                    
                    $tarefasProcessadas[] = $tarefaProcessada;
                }
                
                \Log::info('ProcessarTarefaIA: Processamento concluído', ['total_tarefas' => count($tarefasProcessadas)]);
                
                return response()->json([
                    'success' => true,
                    'tarefas' => $tarefasProcessadas
                ]);
                
            } else {
                \Log::error('ProcessarTarefaIA: Erro na resposta do ChatGPT', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json(['success' => false, 'message' => 'Erro ao processar com IA: ' . $response->body()], 500);
            }
            
        } catch (\Exception $e) {
            \Log::error('ProcessarTarefaIA: Erro geral', [
                'mensagem' => $e->getMessage(),
                'arquivo' => $e->getFile(),
                'linha' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Erro ao processar: ' . $e->getMessage()], 500);
        }
    }

    // MÉTODOS PARA PROJETOS
    public function listarProjetos(Request $request)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        $query = Projeto::with(['cliente', 'colaboradorResponsavel']);

        // Filtro por nome
        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        // Filtro por responsável
        if ($request->filled('responsavel_id')) {
            $query->where('colaborador_responsavel_id', $request->responsavel_id);
        }

        $projetos = $query->orderBy('created_at', 'desc')
                          ->paginate(15)
                          ->withQueryString();

        return view('projetos.index', compact('projetos', 'colaborador'));
    }

    public function criarProjeto()
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        $clientes = Cliente::orderBy('nome')->get();
        $colaboradores = Colaborador::orderBy('nome')->get();

        return view('projetos.create', compact('colaborador', 'clientes', 'colaboradores'));
    }

    public function armazenarProjeto(Request $request)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:2000',
            'cliente_id' => 'required|exists:clientes,id',
            'responsavel_id' => 'required|exists:colaboradores,id',
            'link_repositorio' => 'nullable|url|max:500',
            'prazo_entrega' => 'nullable|date|after:today',
            'status' => 'required|in:planejamento,em_andamento,em_teste,aprovacao_app,concluido,cancelado',
            'anotacoes' => 'nullable|string|max:2000',
        ]);

        // Map field names to match database schema
        $projetoData = [
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'],
            'cliente_id' => $validated['cliente_id'],
            'colaborador_responsavel_id' => $validated['responsavel_id'],
            'repositorio_git' => $validated['link_repositorio'],
            'prazo' => $validated['prazo_entrega'],
            'status' => $validated['status'],
            'anotacoes' => $validated['anotacoes'],
        ];

        $projeto = Projeto::create($projetoData);

        // Processar marcos se enviados
        if ($request->has('marcos')) {
            foreach ($request->marcos as $marco) {
                if (!empty($marco['nome'])) {
                    MarcosProjeto::create([
                        'projeto_id' => $projeto->id,
                        'nome' => $marco['nome'],
                        'prazo' => $marco['prazo'] ?? null,
                        'valor' => 0, // Colaboradores não definem valores
                    ]);
                }
            }
        }

        return redirect('/projetos')->with('success', 'Projeto criado com sucesso!');
    }

    public function verProjeto(Projeto $projeto)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        $projeto->load(['cliente', 'colaboradorResponsavel', 'marcos', 'requisitos', 'tarefas' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return view('projetos.show', compact('projeto', 'colaborador'));
    }

    public function editarProjeto(Projeto $projeto)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        $clientes = Cliente::orderBy('nome')->get();
        $colaboradores = Colaborador::orderBy('nome')->get();
        $projeto->load('marcos');

        return view('projetos.edit', compact('projeto', 'colaborador', 'clientes', 'colaboradores'));
    }

    public function atualizarProjeto(Request $request, Projeto $projeto)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:2000',
            'cliente_id' => 'required|exists:clientes,id',
            'responsavel_id' => 'required|exists:colaboradores,id',
            'link_repositorio' => 'nullable|url|max:500',
            'prazo_entrega' => 'nullable|date|after:today',
            'status' => 'required|in:planejamento,em_andamento,em_teste,aprovacao_app,concluido,cancelado',
            'anotacoes' => 'nullable|string|max:2000',
        ]);

        // Map field names to match database schema
        $projetoData = [
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'],
            'cliente_id' => $validated['cliente_id'],
            'colaborador_responsavel_id' => $validated['responsavel_id'],
            'repositorio_git' => $validated['link_repositorio'],
            'prazo' => $validated['prazo_entrega'],
            'status' => $validated['status'],
            'anotacoes' => $validated['anotacoes'],
        ];

        $projeto->update($projetoData);

        // Atualizar marcos existentes (sem tocar nos valores)
        if ($request->has('marcos')) {
            foreach ($request->marcos as $marcoId => $marcoData) {
                if (is_numeric($marcoId)) {
                    // Marco existente
                    $marco = MarcosProjeto::find($marcoId);
                    if ($marco && $marco->projeto_id == $projeto->id) {
                        $marco->update([
                            'nome' => $marcoData['nome'],
                            'prazo' => $marcoData['prazo'] ?? null,
                        ]);
                    }
                } else {
                    // Novo marco
                    if (!empty($marcoData['nome'])) {
                        MarcosProjeto::create([
                            'projeto_id' => $projeto->id,
                            'nome' => $marcoData['nome'],
                            'prazo' => $marcoData['prazo'] ?? null,
                            'valor' => 0,
                        ]);
                    }
                }
            }
        }

        return redirect('/projetos')->with('success', 'Projeto atualizado com sucesso!');
    }

    private function criarTarefaTeste(Tarefa $tarefaOriginal)
    {
        // Gerar descrição da tarefa de teste usando OpenAI
        $descricaoTeste = $this->openAIService->generateTestTaskDescription(
            $tarefaOriginal->titulo,
            $tarefaOriginal->descricao
        );
        
        // Criar a tarefa de teste
        $tarefaTeste = Tarefa::create([
            'titulo' => "[TESTE] " . $tarefaOriginal->titulo,
            'descricao' => $descricaoTeste,
            'colaborador_id' => $tarefaOriginal->testador_id,
            'projeto_id' => $tarefaOriginal->projeto_id,
            'tipo' => 'teste',
            'prioridade' => $tarefaOriginal->prioridade,
            'status' => 'pendente',
            'tarefa_origem_id' => $tarefaOriginal->id,
        ]);
        
        // Atualizar a tarefa original com o ID da tarefa de teste
        $tarefaOriginal->update(['tarefa_teste_id' => $tarefaTeste->id]);
    }

    // MÉTODOS PARA PLANO DIÁRIO E POMODORO
    public function planoDiario()
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        // Buscar plano do dia atual se existir
        $planoExistente = session('plano_diario_' . now()->format('Y-m-d'));
        
        // Se não há plano na sessão, buscar tarefas já planejadas para hoje
        if (!$planoExistente) {
            $tarefasPlanejadasHoje = Tarefa::where('colaborador_id', $colaborador->id)
                ->where('plano_dia', now()->format('Y-m-d'))
                ->with(['projeto'])
                ->get();
            
            if ($tarefasPlanejadasHoje->count() > 0) {
                // Buscar outras tarefas para organizar próximos dias
                $tarefasRestantes = Tarefa::where('colaborador_id', $colaborador->id)
                    ->whereIn('status', ['pendente', 'em_andamento'])
                    ->whereNull('plano_dia')
                    ->with(['projeto'])
                    ->get();
                
                // Recriar plano baseado nas tarefas já salvas
                $planoExistente = [
                    'data' => now()->format('Y-m-d'),
                    'tarefas_dia' => $tarefasPlanejadasHoje,
                    'tempo_total_estimado' => $tarefasPlanejadasHoje->sum(function($tarefa) {
                        return match($tarefa->prioridade) {
                            'baixa' => 60,
                            'media' => 90,
                            'alta' => 120,
                            'urgente' => 180
                        };
                    }),
                    'configuracao' => [
                        'tempo_pomodoro' => 25
                    ],
                    'proximos_dias' => $this->organizarProximosDias($colaborador->id, $tarefasRestantes)
                ];
                $planoExistente['pomodoros_necessarios'] = ceil($planoExistente['tempo_total_estimado'] / 25);
            }
        }
        
        // Estatísticas básicas
        $tarefasPendentes = Tarefa::where('colaborador_id', $colaborador->id)
            ->where('status', 'pendente')
            ->count();

        $tarefasEmAndamento = Tarefa::where('colaborador_id', $colaborador->id)
            ->where('status', 'em_andamento')
            ->count();

        $tarefasAtrasadas = Tarefa::where('colaborador_id', $colaborador->id)
            ->where('data_vencimento', '<', Carbon::now())
            ->whereIn('status', ['pendente', 'em_andamento'])
            ->count();

        return view('plano-diario', compact(
            'colaborador', 
            'planoExistente', 
            'tarefasPendentes', 
            'tarefasEmAndamento', 
            'tarefasAtrasadas'
        ));
    }

    public function gerarPlanoDiario(Request $request)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'horas_trabalho' => 'required|integer|min:1|max:12',
            'tempo_pomodoro' => 'required|integer|in:25,30,45,60',
            'incluir_atrasadas' => 'boolean',
            'prioridade_minima' => 'required|in:baixa,media,alta,urgente'
        ]);

        // Algoritmo para sugerir tarefas
        $sugestaoTarefas = $this->algoritmoSugestaoTarefas($colaborador->id, $validated);
        
        // Organizar próximos dias
        $proximosDias = $this->organizarProximosDias($colaborador->id, $sugestaoTarefas['nao_selecionadas']);

        $plano = [
            'data' => now()->format('Y-m-d'),
            'configuracao' => $validated,
            'tarefas_dia' => $sugestaoTarefas['selecionadas'],
            'proximos_dias' => $proximosDias,
            'tempo_total_estimado' => $sugestaoTarefas['tempo_total'],
            'pomodoros_necessarios' => ceil($sugestaoTarefas['tempo_total'] / $validated['tempo_pomodoro'])
        ];

        // Salvar na sessão
        session(['plano_diario_' . now()->format('Y-m-d') => $plano]);

        return response()->json([
            'success' => true,
            'plano' => $plano
        ]);
    }

    public function salvarPlanoDiario(Request $request)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'tarefas_selecionadas' => 'required|array',
            'tarefas_selecionadas.*' => 'exists:tarefas,id',
            'organizacao_proximos_dias' => 'nullable|array'
        ]);

        // Atualizar tarefas selecionadas para hoje
        $hoje = now()->format('Y-m-d');
        foreach ($validated['tarefas_selecionadas'] as $tarefaId) {
            Tarefa::where('id', $tarefaId)
                  ->where('colaborador_id', $colaborador->id)
                  ->update(['plano_dia' => $hoje]);
        }

        // Limpar plano anterior das outras tarefas
        Tarefa::where('colaborador_id', $colaborador->id)
              ->where('plano_dia', $hoje)
              ->whereNotIn('id', $validated['tarefas_selecionadas'])
              ->update(['plano_dia' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Plano diário salvo com sucesso! Boa produtividade! 🚀'
        ]);
    }

    public function limparPlanoDiario()
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return response()->json(['error' => 'Não autorizado'], 401);
        }

        // Limpar plano do dia de todas as tarefas do colaborador
        $hoje = now()->format('Y-m-d');
        Tarefa::where('colaborador_id', $colaborador->id)
              ->where('plano_dia', $hoje)
              ->update(['plano_dia' => null]);

        // Limpar sessão
        session()->forget('plano_diario_' . $hoje);

        return response()->json([
            'success' => true,
            'message' => 'Plano diário limpo com sucesso!'
        ]);
    }

    public function registrarPomodoro(Request $request, Tarefa $tarefa)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador || $tarefa->colaborador_id != $colaborador->id) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $validated = $request->validate([
            'duracao' => 'required|integer|min:1|max:120',
            'tipo' => 'required|in:foco,pausa_curta,pausa_longa',
            'observacoes' => 'nullable|string|max:500'
        ]);

        // Registrar sessão pomodoro
        $pomodoros = $tarefa->pomodoros ?? [];
        $pomodoros[] = [
            'data' => now()->toISOString(),
            'duracao' => $validated['duracao'],
            'tipo' => $validated['tipo'],
            'observacoes' => $validated['observacoes'] ?? null
        ];

        $tarefa->update(['pomodoros' => $pomodoros]);

        return response()->json([
            'success' => true,
            'total_pomodoros' => count(array_filter($pomodoros, fn($p) => $p['tipo'] === 'foco')),
            'tempo_total' => array_sum(array_column(array_filter($pomodoros, fn($p) => $p['tipo'] === 'foco'), 'duracao'))
        ]);
    }

    private function algoritmoSugestaoTarefas($colaboradorId, $config)
    {
        $query = Tarefa::where('colaborador_id', $colaboradorId)
                      ->whereIn('status', ['pendente', 'em_andamento']);

        // Incluir tarefas atrasadas se solicitado
        if ($config['incluir_atrasadas']) {
            $query->where(function($q) {
                $q->where('data_vencimento', '>=', Carbon::now())
                  ->orWhere('data_vencimento', '<', Carbon::now());
            });
        } else {
            $query->where(function($q) {
                $q->where('data_vencimento', '>=', Carbon::now())
                  ->orWhereNull('data_vencimento');
            });
        }

        // Filtrar por prioridade mínima
        $prioridades = ['baixa' => 1, 'media' => 2, 'alta' => 3, 'urgente' => 4];
        $prioridadeMinima = $prioridades[$config['prioridade_minima']];
        
        $query->whereIn('prioridade', array_keys(array_filter($prioridades, fn($p) => $p >= $prioridadeMinima)));

        $tarefasDisponiveis = $query->with(['projeto'])->get();

        // Algoritmo de pontuação para seleção
        $tarefasComPontuacao = $tarefasDisponiveis->map(function($tarefa) {
            $pontuacao = 0;
            
            // Pontuação por prioridade
            $pontuacaoPrioridade = ['baixa' => 1, 'media' => 2, 'alta' => 3, 'urgente' => 4];
            $pontuacao += $pontuacaoPrioridade[$tarefa->prioridade] * 10;
            
            // Pontuação por vencimento
            if ($tarefa->data_vencimento) {
                $diasParaVencimento = Carbon::now()->diffInDays($tarefa->data_vencimento, false);
                if ($diasParaVencimento < 0) {
                    $pontuacao += 50; // Atrasada
                } elseif ($diasParaVencimento <= 1) {
                    $pontuacao += 30; // Vence hoje/amanhã
                } elseif ($diasParaVencimento <= 7) {
                    $pontuacao += 15; // Vence esta semana
                }
            }
            
            // Pontuação por status
            if ($tarefa->status === 'em_andamento') {
                $pontuacao += 20; // Já iniciada
            }
            
            // Estimativa de tempo (assumindo 1-3 horas por tarefa baseado na prioridade)
            $tempoEstimado = match($tarefa->prioridade) {
                'baixa' => 60,    // 1 hora
                'media' => 90,    // 1.5 horas
                'alta' => 120,    // 2 horas
                'urgente' => 180  // 3 horas
            };
            
            $tarefa->tempo_estimado = $tempoEstimado;
            $tarefa->pontuacao = $pontuacao;
            
            return $tarefa;
        });

        // Ordenar por pontuação
        $tarefasOrdenadas = $tarefasComPontuacao->sortByDesc('pontuacao');
        
        // Selecionar tarefas que cabem no tempo disponível
        $tempoDisponivel = $config['horas_trabalho'] * 60; // em minutos
        $tempoAcumulado = 0;
        $tarefasSelecionadas = collect();
        
        foreach ($tarefasOrdenadas as $tarefa) {
            if ($tempoAcumulado + $tarefa->tempo_estimado <= $tempoDisponivel) {
                $tarefasSelecionadas->push($tarefa);
                $tempoAcumulado += $tarefa->tempo_estimado;
            }
        }

        return [
            'selecionadas' => $tarefasSelecionadas,
            'nao_selecionadas' => $tarefasOrdenadas->diff($tarefasSelecionadas),
            'tempo_total' => $tempoAcumulado
        ];
    }

    private function organizarProximosDias($colaboradorId, $tarefasRestantes)
    {
        $proximosDias = [];
        $tarefasArray = $tarefasRestantes->toArray();
        $currentDay = 1;
        $maxDays = 10; // Máximo 10 dias para encontrar 5 dias úteis
        $diasProcessados = 0;
        
        while ($diasProcessados < 5 && $currentDay <= $maxDays && !empty($tarefasArray)) {
            $data = Carbon::now()->addDays($currentDay);
            
            // Pular fins de semana
            if ($data->isWeekend()) {
                $currentDay++;
                continue;
            }
            
            // Pegar até 3 tarefas para este dia
            $tarefasDoDia = array_splice($tarefasArray, 0, 3);
            
            $proximosDias[] = [
                'data' => $data->format('Y-m-d'),
                'dia_semana' => $data->locale('pt')->translatedFormat('l'),
                'data_formatada' => $data->format('d/m'),
                'tarefas' => $tarefasDoDia
            ];
            
            $diasProcessados++;
            $currentDay++;
        }
        
        // Sempre mostrar pelo menos 5 dias, mesmo que sem tarefas
        while ($diasProcessados < 5 && $currentDay <= $maxDays) {
            $data = Carbon::now()->addDays($currentDay);
            
            if (!$data->isWeekend()) {
                $proximosDias[] = [
                    'data' => $data->format('Y-m-d'),
                    'dia_semana' => $data->locale('pt')->translatedFormat('l'),
                    'data_formatada' => $data->format('d/m'),
                    'tarefas' => []
                ];
                $diasProcessados++;
            }
            $currentDay++;
        }
        
        return $proximosDias;
    }

    public function tutoriais()
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        $tutoriais = Tutorial::ativos()
            ->paraColaboradores()
            ->ordenados()
            ->get();

        return view('tutoriais-colaboradores', compact('tutoriais'));
    }
    
    public function tarefasDesignadas(Request $request)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        $query = Tarefa::where('created_by', $colaborador->id)
                       ->where('colaborador_id', '!=', $colaborador->id)
                       ->with(['projeto', 'colaborador']);

        // Filtros
        if ($request->filled('status')) {
            if ($request->status == 'pendente_em_andamento') {
                $query->whereIn('status', ['pendente', 'em_andamento']);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('colaborador_id')) {
            $query->where('colaborador_id', $request->colaborador_id);
        }

        if ($request->filled('projeto_id')) {
            $query->where('projeto_id', $request->projeto_id);
        }

        $tarefas = $query->orderBy('created_at', 'desc')
                        ->paginate(15);

        // Para os filtros
        $colaboradores = Colaborador::whereHas('tarefas', function($q) use ($colaborador) {
            $q->where('created_by', $colaborador->id);
        })->get();
        
        $projetos = Projeto::whereHas('tarefas', function($q) use ($colaborador) {
            $q->where('created_by', $colaborador->id);
        })->get();

        return view('tarefas-designadas', compact('tarefas', 'colaboradores', 'projetos', 'colaborador'));
    }

    public function pausarTarefa(Tarefa $tarefa)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador || $tarefa->colaborador_id != $colaborador->id) {
            return redirect('/dashboard')->with('error', 'Acesso negado!');
        }

        if ($tarefa->status !== 'em_andamento' || $tarefa->pausada) {
            return redirect()->back()->with('error', 'Tarefa não pode ser pausada!');
        }

        $tarefa->pausarTarefa();

        return redirect()->back()->with('success', 'Tarefa pausada com sucesso!');
    }

    public function continuarTarefa(Tarefa $tarefa)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador || $tarefa->colaborador_id != $colaborador->id) {
            return redirect('/dashboard')->with('error', 'Acesso negado!');
        }

        if ($tarefa->status !== 'em_andamento' || !$tarefa->pausada) {
            return redirect()->back()->with('error', 'Tarefa não pode ser continuada!');
        }

        $tarefa->continuarTarefa();

        return redirect()->back()->with('success', 'Tarefa retomada com sucesso!');
    }

    public function adicionarNota(Request $request, Tarefa $tarefa)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador || $tarefa->colaborador_id != $colaborador->id) {
            return redirect('/dashboard')->with('error', 'Acesso negado!');
        }

        $validated = $request->validate([
            'nota' => 'required|string|max:500'
        ]);

        $tarefa->adicionarNota($validated['nota']);

        return redirect()->back()->with('success', 'Nota adicionada com sucesso!');
    }

    public function transferirTarefa(Request $request, Tarefa $tarefa)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador || $tarefa->colaborador_id != $colaborador->id) {
            return redirect('/dashboard')->with('error', 'Acesso negado!');
        }

        $validated = $request->validate([
            'colaborador_id' => 'required|exists:colaboradores,id|different:' . $tarefa->colaborador_id,
            'motivo' => 'required|string|max:1000'
        ]);

        if ($tarefa->status === 'concluida' || $tarefa->status === 'cancelada') {
            return redirect()->back()->with('error', 'Não é possível transferir tarefas concluídas ou canceladas!');
        }

        $tarefa->transferirResponsabilidade($validated['colaborador_id'], $validated['motivo']);

        return redirect()->back()->with('success', 'Tarefa transferida com sucesso!');
    }
    
    public function exportarTarefas(Request $request)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'projeto_id' => 'required|exists:projetos,id'
        ]);

        // Buscar tarefas pendentes do projeto para o colaborador
        $tarefas = Tarefa::where('colaborador_id', $colaborador->id)
            ->where('projeto_id', $validated['projeto_id'])
            ->whereIn('status', ['pendente', 'em_andamento'])
            ->orderBy('prioridade', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        $projeto = Projeto::find($validated['projeto_id']);

        // Formatar o texto
        $texto = "TAREFAS DO PROJETO: " . $projeto->nome . "\n";
        $texto .= "Data de exportação: " . now()->format('d/m/Y H:i') . "\n";
        $texto .= "Total de tarefas pendentes: " . $tarefas->count() . "\n";
        $texto .= str_repeat('=', 80) . "\n\n";

        foreach ($tarefas as $index => $tarefa) {
            $texto .= ($index + 1) . ". " . $tarefa->titulo . "\n";
            
            if ($tarefa->descricao) {
                $texto .= "Descrição: " . $tarefa->descricao . "\n";
            }
            
            $texto .= "Status: " . ucfirst(str_replace('_', ' ', $tarefa->status)) . "\n";
            $texto .= "Prioridade: " . ucfirst($tarefa->prioridade) . "\n";
            
            if ($tarefa->data_vencimento) {
                $texto .= "Vencimento: " . $tarefa->data_vencimento->format('d/m/Y') . "\n";
            }
            
            $texto .= "\n" . str_repeat('-', 60) . "\n\n";
        }

        // Criar response com o texto
        return response($texto)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="tarefas_' . Str::slug($projeto->nome) . '_' . now()->format('Y-m-d') . '.txt"');
    }

    // Tarefas de todos colaboradores (apenas para Administrativo)
    public function todasTarefas(Request $request)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador || ($colaborador->setor_id !== 3 && $colaborador->setor->nome !== 'Administrativo')) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado. Esta página é exclusiva para o setor Administrativo.');
        }

        $query = Tarefa::with(['colaborador', 'projeto', 'criador']);

        // Filtro por colaborador
        if ($request->has('colaborador_id') && $request->colaborador_id) {
            $query->where('colaborador_id', $request->colaborador_id);
        }

        // Filtro por status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filtro por data
        if ($request->has('data_inicio') && $request->data_inicio) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }
        
        if ($request->has('data_fim') && $request->data_fim) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        // Filtro por prioridade
        if ($request->has('prioridade') && $request->prioridade) {
            $query->where('prioridade', $request->prioridade);
        }

        // Filtro por projeto
        if ($request->has('projeto_id') && $request->projeto_id) {
            $query->where('projeto_id', $request->projeto_id);
        }

        $tarefas = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Buscar todos colaboradores e projetos para os filtros
        $colaboradores = Colaborador::orderBy('nome')->get();
        $projetos = Projeto::orderBy('nome')->get();

        return view('todas-tarefas', compact('tarefas', 'colaboradores', 'projetos'));
    }
    
    public function desempenhoTime(Request $request)
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }
        
        // Período selecionado (padrão: últimos 30 dias)
        $periodo = $request->get('periodo', '30');
        $dataInicio = Carbon::now()->subDays($periodo);
        $dataFim = Carbon::now();
        
        // Buscar todos os colaboradores
        $colaboradores = Colaborador::with(['tarefas' => function($query) use ($dataInicio, $dataFim) {
            $query->whereBetween('updated_at', [$dataInicio, $dataFim]);
        }])->get();
        
        // Calcular métricas para cada colaborador
        $metricas = [];
        foreach ($colaboradores as $colab) {
            $tarefasConcluidas = $colab->tarefas->where('status', 'concluida')->count();
            $tarefasTotal = $colab->tarefas->count();
            
            // Média de tarefas concluídas por dia
            $diasTrabalhados = $colab->tarefas->where('status', 'concluida')
                ->groupBy(function($tarefa) {
                    return $tarefa->updated_at->format('Y-m-d');
                })->count();
            
            $mediaPorDia = $diasTrabalhados > 0 ? round($tarefasConcluidas / $diasTrabalhados, 2) : 0;
            
            // Projetos trabalhados
            $projetosTrabalhados = $colab->tarefas->pluck('projeto_id')->filter()->unique()->count();
            
            // Tempo médio para finalizar tarefas (em horas)
            $tempoMedio = 0;
            $tarefasComTempo = $colab->tarefas->where('status', 'concluida')
                ->filter(function($tarefa) {
                    return $tarefa->created_at && $tarefa->updated_at;
                });
            
            if ($tarefasComTempo->count() > 0) {
                $totalHoras = 0;
                foreach ($tarefasComTempo as $tarefa) {
                    $inicio = Carbon::parse($tarefa->created_at);
                    $fim = Carbon::parse($tarefa->updated_at);
                    $horasDiff = $fim->diffInHours($inicio);
                    // Limitar a 720 horas (30 dias) para evitar distorções
                    $totalHoras += min($horasDiff, 720);
                }
                $tempoMedio = round($totalHoras / $tarefasComTempo->count(), 1);
            }
            
            // Buscar projetos onde o colaborador é responsável e estão concluídos no período
            $projetosResponsavel = Projeto::where('colaborador_responsavel_id', $colab->id)
                ->whereHas('statusProjeto', function($query) {
                    $query->where('nome', 'Concluído')
                          ->orWhere('nome', 'concluido');
                })
                ->whereBetween('updated_at', [$dataInicio, $dataFim])
                ->count();
            
            // Pontuação total (base para ranking)
            $pontuacao = ($tarefasConcluidas * 10) + ($mediaPorDia * 5) + ($projetosTrabalhados * 3) + ($projetosResponsavel * 100);
            
            $metricas[] = [
                'colaborador' => $colab,
                'tarefas_concluidas' => $tarefasConcluidas,
                'tarefas_total' => $tarefasTotal,
                'media_por_dia' => $mediaPorDia,
                'projetos_trabalhados' => $projetosTrabalhados,
                'projetos_responsavel' => $projetosResponsavel,
                'tempo_medio' => $tempoMedio,
                'pontuacao' => $pontuacao
            ];
        }
        
        // Ordenar por pontuação (ranking)
        usort($metricas, function($a, $b) {
            return $b['pontuacao'] <=> $a['pontuacao'];
        });
        
        // Definir prêmios
        $premiosMensais = [
            1 => [
                'titulo' => '1º Lugar - Campeão do Mês',
                'premio' => 'Almoço no Wecker + Dia de Folga + Certificado',
                'descricao' => 'Dia de folga em qualquer dia útil (segunda a sexta) + Certificado especial de destaque do mês'
            ],
            2 => [
                'titulo' => '2º Lugar - Vice-Campeão',
                'premio' => 'Dia de Folga + Certificado',
                'descricao' => 'Dia de folga de segunda a quinta (exceto sexta) + Certificado especial de destaque do mês'
            ],
            3 => [
                'titulo' => '3º Lugar - Bronze',
                'premio' => 'Certificado de Reconhecimento',
                'descricao' => 'Certificado especial de destaque do mês'
            ]
        ];
        
        $premiosAnuais = [
            1 => [
                'titulo' => '1º Lugar - Campeão Anual',
                'premio' => 'MacBook M1',
                'descricao' => 'Notebook Apple MacBook Air M1 (sorteado em dezembro)'
            ],
            2 => [
                'titulo' => '2º Lugar - Vice-Campeão Anual',
                'premio' => 'iPhone 14',
                'descricao' => 'iPhone 14 128GB (sorteado em dezembro)'
            ]
        ];
        
        // Ranking atual
        $rankingAtual = [];
        foreach ($metricas as $index => $metrica) {
            if ($index < 3) {
                $rankingAtual[] = [
                    'posicao' => $index + 1,
                    'colaborador' => $metrica['colaborador'],
                    'pontuacao' => $metrica['pontuacao'],
                    'premio' => $premiosMensais[$index + 1] ?? null
                ];
            }
        }
        
        return view('desempenho-time', compact(
            'metricas',
            'rankingAtual',
            'premiosMensais',
            'premiosAnuais',
            'periodo',
            'dataInicio',
            'dataFim'
        ));
    }
}