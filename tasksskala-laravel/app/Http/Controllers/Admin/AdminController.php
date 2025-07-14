<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Colaborador;
use App\Models\Setor;
use App\Models\Conhecimento;

class AdminController extends Controller
{
    public function index()
    {
        $totalColaboradores = Colaborador::count();
        $totalSetores = Setor::count();
        $totalConhecimentos = Conhecimento::count();
        
        return view('admin.dashboard', compact('totalColaboradores', 'totalSetores', 'totalConhecimentos'));
    }
}
