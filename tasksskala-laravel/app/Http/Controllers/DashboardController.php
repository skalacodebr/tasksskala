<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Models\Projeto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
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

        return view('dashboard', compact(
            'colaborador',
            'tarefasPendentes',
            'tarefasEmAndamento', 
            'tarefasConcluidas',
            'tarefasAtrasadas',
            'tarefasRecentes',
            'tarefasPrioridade',
            'proximasTarefas'
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
}