<?php

namespace App\Http\Controllers;

use App\Models\SkalaTask;
use App\Models\SkalaPlan;

class AgenteSkalaController extends Controller
{
    public function index()
    {
        $tasks = SkalaTask::with('plans')->orderBy('created_at', 'desc')->get();
        
        return view('agente-skala.index', compact('tasks'));
    }

    public function show($id)
    {
        $task = SkalaTask::with('plans')->findOrFail($id);
        
        return view('agente-skala.show', compact('task'));
    }
}