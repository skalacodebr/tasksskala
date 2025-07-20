<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkalaTask;
use App\Models\SkalaPlan;
use Illuminate\Http\Request;

class AgenteSkalaController extends Controller
{
    public function index(Request $request)
    {
        $query = SkalaTask::with('plans');
        
        // Filtro por repositório
        if ($request->filled('repository_filter')) {
            $query->where('repository_url', $request->repository_filter);
        }
        
        $tasks = $query->orderBy('created_at', 'desc')->get();
        
        // Obter todos os repositórios únicos para o filtro
        $repositories = SkalaTask::whereNotNull('repository_url')
                                ->distinct()
                                ->pluck('repository_url')
                                ->sort();
        
        // Calcular custo total dos planos
        $totalCost = 0;
        $planCount = 0;
        
        foreach ($tasks as $task) {
            foreach ($task->plans as $plan) {
                if ($plan->plan_json && isset($plan->plan_json['total_cost_usd'])) {
                    $totalCost += $plan->plan_json['total_cost_usd'];
                    $planCount++;
                }
            }
        }
        
        return view('admin.agente-skala.index', compact('tasks', 'repositories', 'totalCost', 'planCount'));
    }

    public function show($id)
    {
        $task = SkalaTask::with('plans')->findOrFail($id);
        
        return view('admin.agente-skala.show', compact('task'));
    }

    public function updatePlanStatus(Request $request, $planId)
    {
        $plan = SkalaPlan::findOrFail($planId);
        
        $plan->approved = $request->input('approved');
        $plan->save();
        
        // Atualizar status da task vinculada
        $task = $plan->task;
        if ($task) {
            if ($plan->approved) {
                $task->status = 'aprovado';
                $task->save();
            }
        }
        
        $status = $plan->approved ? 'aprovado' : 'reprovado';
        
        return redirect()->back()->with('success', "Plano #{$plan->id} {$status} com sucesso! Task #{$task->id} também foi atualizada.");
    }
}