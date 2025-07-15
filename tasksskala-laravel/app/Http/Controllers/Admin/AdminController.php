<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Colaborador;
use App\Models\Setor;
use App\Models\Conhecimento;
use App\Models\Projeto;
use App\Models\Tarefa;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $totalColaboradores = Colaborador::count();
        $totalSetores = Setor::count();
        $totalConhecimentos = Conhecimento::count();
        
        // Projetos atrasados
        $projetosAtrasados = Projeto::where('prazo', '<', Carbon::now())
            ->whereNotIn('status', ['concluido', 'cancelado'])
            ->count();
        
        // Projetos com entrega neste mês
        $projetosEsteMes = Projeto::whereYear('prazo', Carbon::now()->year)
            ->whereMonth('prazo', Carbon::now()->month)
            ->whereNotIn('status', ['concluido', 'cancelado'])
            ->count();
        
        // Projetos com entrega no próximo mês
        $proximoMes = Carbon::now()->addMonth();
        $projetosProximoMes = Projeto::whereYear('prazo', $proximoMes->year)
            ->whereMonth('prazo', $proximoMes->month)
            ->whereNotIn('status', ['concluido', 'cancelado'])
            ->count();
        
        // Projetos por status para gráfico de pizza
        $projetosPorStatus = Projeto::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        // Colaboradores vs número de tarefas para gráfico de barras
        $colaboradoresTarefas = Colaborador::withCount('tarefas')
            ->orderBy('tarefas_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($colaborador) {
                return [
                    'nome' => $colaborador->nome,
                    'tarefas_count' => $colaborador->tarefas_count
                ];
            });
        
        return view('admin.dashboard', compact(
            'totalColaboradores', 
            'totalSetores', 
            'totalConhecimentos',
            'projetosAtrasados',
            'projetosEsteMes',
            'projetosProximoMes',
            'projetosPorStatus',
            'colaboradoresTarefas'
        ));
    }
}
