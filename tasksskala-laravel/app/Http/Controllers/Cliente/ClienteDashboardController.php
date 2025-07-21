<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SkalaTask;
use App\Models\Projeto;

class ClienteDashboardController extends Controller
{
    public function index()
    {
        $cliente = Auth::guard('cliente')->user();
        $projetos = $cliente->projetos;
        
        // Buscar tasks do cliente no banco externo
        $tasks = SkalaTask::where('user_id', $cliente->id)->orderBy('created_at', 'desc')->get();
        
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

        // Concatenar URL e descrição
        $taskDescription = "URL: {$request->url_pagina}\n\nDescrição: {$request->descricao}";

        // Criar task no banco externo
        SkalaTask::create([
            'repository_url' => $projeto->repositorio_git,
            'task_description' => $taskDescription,
            'status' => 'pendente',
            'user_id' => $cliente->id,
        ]);

        return redirect()->route('cliente.dashboard')->with('success', 'Task criada com sucesso! Nossa equipe irá analisá-la em breve.');
    }

    public function minhasTasks()
    {
        $cliente = Auth::guard('cliente')->user();
        $tasks = SkalaTask::where('user_id', $cliente->id)->orderBy('created_at', 'desc')->get();
        
        return view('cliente.minhas-tasks', compact('tasks'));
    }

    public function verTask($id)
    {
        $cliente = Auth::guard('cliente')->user();
        $task = SkalaTask::where('id', $id)
                        ->where('user_id', $cliente->id)
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
}