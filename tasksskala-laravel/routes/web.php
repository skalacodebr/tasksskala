<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ColaboradorController;
use App\Http\Controllers\Admin\SetorController;
use App\Http\Controllers\Admin\ConhecimentoController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\ProjetoController;
use App\Http\Controllers\Admin\TarefaController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

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
