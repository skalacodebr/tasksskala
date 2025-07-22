<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SkalaTask;
use App\Models\Projeto;
use App\Models\Tutorial;
use App\Models\Feedback;

class ClienteDashboardController extends Controller
{
    public function index()
    {
        $cliente = Auth::guard('cliente')->user();
        $projetos = $cliente->projetos;
        
        // Buscar tasks do cliente no banco externo
        // Filtrar por repositório dos projetos do cliente
        $repositoriosCliente = $cliente->projetos->pluck('repositorio_git')->filter()->toArray();
        $tasks = SkalaTask::whereIn('repository_url', $repositoriosCliente)
                         ->orderBy('created_at', 'desc')
                         ->get();
        
        return view('cliente.dashboard', compact('cliente', 'projetos', 'tasks'));
    }

    public function criarTask()
    {
        $cliente = Auth::guard('cliente')->user();
        $projetos = $cliente->projetos;
        
        return view('cliente.criar-task', compact('projetos'));
    }

    public function armazenarTask(Request $request)
    {
        $request->validate([
            'projeto_id' => 'required|exists:projetos,id',
            'url_pagina' => 'required|url',
            'descricao' => 'required|string|min:10',
        ]);

        $cliente = Auth::guard('cliente')->user();
        $projeto = Projeto::where('id', $request->projeto_id)
                          ->where('cliente_id', $cliente->id)
                          ->firstOrFail();

        // Concatenar informações do cliente, URL e descrição
        $taskDescription = "Cliente: {$cliente->nome} (ID: {$cliente->id})\nProjeto: {$projeto->nome}\nURL: {$request->url_pagina}\n\nDescrição: {$request->descricao}";

        // Criar task no banco externo
        // Usar user_id fixo para tasks de clientes (user_id 1 deve existir no banco skala_tasks)
        SkalaTask::create([
            'repository_url' => $projeto->repositorio_git,
            'task_description' => $taskDescription,
            'status' => 'pendente',
   
        ]);

        return redirect()->route('cliente.dashboard')->with('success', 'Task criada com sucesso! Nossa equipe irá analisá-la em breve.');
    }

    public function minhasTasks()
    {
        $cliente = Auth::guard('cliente')->user();
        // Filtrar por repositório dos projetos do cliente
        $repositoriosCliente = $cliente->projetos->pluck('repositorio_git')->filter()->toArray();
        $tasks = SkalaTask::whereIn('repository_url', $repositoriosCliente)
                         ->orderBy('created_at', 'desc')
                         ->get();
        
        return view('cliente.minhas-tasks', compact('tasks'));
    }

    public function verTask($id)
    {
        $cliente = Auth::guard('cliente')->user();
        // Verificar se a task pertence a um dos projetos do cliente
        $repositoriosCliente = $cliente->projetos->pluck('repositorio_git')->filter()->toArray();
        $task = SkalaTask::where('id', $id)
                        ->whereIn('repository_url', $repositoriosCliente)
                        ->with('plans')
                        ->firstOrFail();
        
        return view('cliente.task-detalhes', compact('task'));
    }

    public function meusProjetos()
    {
        $cliente = Auth::guard('cliente')->user();
        $projetos = $cliente->projetos()->with(['colaboradorResponsavel', 'statusProjeto'])->get();
        
        return view('cliente.projetos', compact('projetos'));
    }

    public function verProjeto(Projeto $projeto)
    {
        $cliente = Auth::guard('cliente')->user();
        
        // Verificar se o projeto pertence ao cliente logado
        if ($projeto->cliente_id !== $cliente->id) {
            abort(403, 'Acesso negado');
        }

        $projeto->load(['colaboradorResponsavel', 'statusProjeto', 'marcos']);
        
        return view('cliente.projeto-detalhes', compact('projeto'));
    }

    public function tutoriais()
    {
        $cliente = Auth::guard('cliente')->user();
        
        $tutoriais = Tutorial::ativos()
            ->paraClientes()
            ->ordenados()
            ->get();

        return view('cliente.tutoriais', compact('tutoriais'));
    }

    public function feedbacks()
    {
        $cliente = Auth::guard('cliente')->user();
        
        $feedbacks = Feedback::where('cliente_id', $cliente->id)
            ->with(['projeto', 'respondidoPor'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('cliente.feedbacks.index', compact('feedbacks'));
    }

    public function criarFeedback()
    {
        $cliente = Auth::guard('cliente')->user();
        $projetos = $cliente->projetos;
        
        return view('cliente.feedbacks.create', compact('projetos'));
    }

    public function armazenarFeedback(Request $request)
    {
        $cliente = Auth::guard('cliente')->user();
        
        $validated = $request->validate([
            'tipo' => 'required|in:sugestao,reclamacao,elogio,duvida,outro',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
            'projeto_id' => 'nullable|exists:projetos,id',
            'assunto' => 'required|string|max:255',
            'mensagem' => 'required|string|min:10',
        ]);
        
        // Verificar se o projeto pertence ao cliente
        if ($validated['projeto_id']) {
            $projeto = Projeto::where('id', $validated['projeto_id'])
                ->where('cliente_id', $cliente->id)
                ->firstOrFail();
        }
        
        $validated['cliente_id'] = $cliente->id;
        
        Feedback::create($validated);
        
        return redirect()->route('cliente.feedbacks')
            ->with('success', 'Feedback enviado com sucesso! Entraremos em contato em breve.');
    }

    public function verFeedback(Feedback $feedback)
    {
        $cliente = Auth::guard('cliente')->user();
        
        // Verificar se o feedback pertence ao cliente
        if ($feedback->cliente_id !== $cliente->id) {
            abort(403, 'Acesso negado');
        }
        
        return view('cliente.feedbacks.show', compact('feedback'));
    }

    public function avaliarFeedback(Request $request, Feedback $feedback)
    {
        $cliente = Auth::guard('cliente')->user();
        
        // Verificar se o feedback pertence ao cliente e está respondido
        if ($feedback->cliente_id !== $cliente->id || $feedback->status !== 'respondido') {
            abort(403, 'Acesso negado');
        }
        
        $validated = $request->validate([
            'avaliacao' => 'required|integer|min:1|max:5',
        ]);
        
        $feedback->update([
            'avaliacao' => $validated['avaliacao'],
            'status' => 'resolvido'
        ]);
        
        return redirect()->route('cliente.feedback.show', $feedback)
            ->with('success', 'Obrigado pela sua avaliação!');
    }
}