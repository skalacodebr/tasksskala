<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ColaboradorController;
use App\Http\Controllers\Admin\SetorController;
use App\Http\Controllers\Admin\ConhecimentoController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\ProjetoController;
use App\Http\Controllers\Admin\TarefaController;
use App\Http\Controllers\Auth\ColaboradorAuthController;
use App\Http\Controllers\DashboardController;

// Rota principal - redireciona para dashboard se logado, senão para login
Route::get('/', function () {
    if (session('colaborador_id')) {
        return redirect('/dashboard');
    }
    return redirect('/login');
})->name('home');

// Rotas de autenticação do colaborador
Route::get('/login', [ColaboradorAuthController::class, 'showLoginForm'])->name('colaborador.login.form');
Route::post('/login', [ColaboradorAuthController::class, 'login'])->name('colaborador.login');
Route::post('/logout', [ColaboradorAuthController::class, 'logout'])->name('colaborador.logout');

// Rotas do colaborador (dashboard)
Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/minhas-tarefas', [DashboardController::class, 'minhasTarefas'])->name('minhas-tarefas');
    Route::patch('/tarefa/{tarefa}/iniciar', [DashboardController::class, 'iniciarTarefa'])->name('tarefa.iniciar');
    Route::patch('/tarefa/{tarefa}/concluir', [DashboardController::class, 'concluirTarefa'])->name('tarefa.concluir');
    Route::get('/tarefa/{tarefa}/detalhes', [DashboardController::class, 'verTarefa'])->name('tarefa.detalhes');
});

// Rotas do Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('colaboradores', ColaboradorController::class)->parameters([
        'colaboradores' => 'colaborador'
    ]);
    Route::resource('setores', SetorController::class);
    Route::resource('conhecimentos', ConhecimentoController::class);
    Route::resource('clientes', ClienteController::class)->parameters([
        'clientes' => 'cliente'
    ]);
    Route::resource('projetos', ProjetoController::class)->parameters([
        'projetos' => 'projeto'
    ]);
    Route::resource('tarefas', TarefaController::class)->parameters([
        'tarefas' => 'tarefa'
    ]);
    
    Route::patch('tarefas/{tarefa}/iniciar', [TarefaController::class, 'iniciar'])->name('tarefas.iniciar');
    Route::patch('tarefas/{tarefa}/concluir', [TarefaController::class, 'concluir'])->name('tarefas.concluir');
    Route::patch('tarefas/{tarefa}/cancelar', [TarefaController::class, 'cancelar'])->name('tarefas.cancelar');
});
