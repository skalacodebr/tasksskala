<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkalaTask;
use App\Models\SkalaPlan;

class AgenteSkalaController extends Controller
{
    public function index()
    {
        $tasks = SkalaTask::with('plans')->orderBy('created_at', 'desc')->get();
        
        return view('admin.agente-skala.index', compact('tasks'));
    }

    public function show($id)
    {
        $task = SkalaTask::with('plans')->findOrFail($id);
        
        return view('admin.agente-skala.show', compact('task'));
    }
}