<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Models\Projeto;
use App\Models\Colaborador;
use App\Models\Cliente;
use App\Models\MarcosProjeto;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private GoogleCalendarService $googleCalendarService;
    
    public function __construct(GoogleCalendarService $googleCalendarService)
    {
        $this->googleCalendarService = $googleCalendarService;
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
            $query->where('status', $request->status);
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

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:2000',
            'colaborador_id' => 'required|exists:colaboradores,id',
            'projeto_id' => 'nullable|exists:projetos,id',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
            'data_vencimento' => 'nullable|date|after:now',
            'recorrente' => 'boolean',
            'frequencia_recorrencia' => 'nullable|in:diaria,semanal,mensal|required_if:recorrente,1',
        ]);

        $validated['tipo'] = 'manual';
        $validated['status'] = 'pendente';

        Tarefa::create($validated);

        return redirect('/minhas-tarefas')->with('success', 'Tarefa criada com sucesso!');
    }

    // MÉTODOS PARA PROJETOS
    public function listarProjetos()
    {
        $colaborador = session('colaborador');
        
        if (!$colaborador) {
            return redirect('/login');
        }

        $projetos = Projeto::with(['cliente', 'colaboradorResponsavel'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

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

        $projeto->load(['cliente', 'colaboradorResponsavel', 'marcos', 'tarefas' => function($query) {
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
}