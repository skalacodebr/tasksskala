<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ColaboradorController;
use App\Http\Controllers\Admin\SetorController;
use App\Http\Controllers\Admin\ConhecimentoController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\ProjetoController;
use App\Http\Controllers\Admin\TarefaController;
use App\Http\Controllers\Admin\StatusProjetoController;
use App\Http\Controllers\Admin\AgenteSkalaController as AdminAgenteSkalaController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Auth\ColaboradorAuthController;
use App\Http\Controllers\Auth\ClienteAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AgenteSkalaController;
use App\Http\Controllers\Cliente\ClienteDashboardController;

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

// Rotas de autenticação do cliente
Route::get('/cliente/login', [ClienteAuthController::class, 'showLoginForm'])->name('cliente.login.form');
Route::post('/cliente/login', [ClienteAuthController::class, 'login'])->name('cliente.login');
Route::post('/cliente/logout', [ClienteAuthController::class, 'logout'])->name('cliente.logout');

// Rotas do colaborador (dashboard)
Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/minhas-tarefas', [DashboardController::class, 'minhasTarefas'])->name('minhas-tarefas');
    Route::get('/tarefa/criar', [DashboardController::class, 'criarTarefa'])->name('tarefa.criar');
    Route::post('/tarefa/criar', [DashboardController::class, 'armazenarTarefa'])->name('tarefa.store');
    Route::patch('/tarefa/{tarefa}/iniciar', [DashboardController::class, 'iniciarTarefa'])->name('tarefa.iniciar');
    Route::patch('/tarefa/{tarefa}/concluir', [DashboardController::class, 'concluirTarefa'])->name('tarefa.concluir');
    Route::get('/tarefa/{tarefa}/detalhes', [DashboardController::class, 'verTarefa'])->name('tarefa.detalhes');
    
    // Plano de ação diário e Pomodoro
    Route::get('/plano-diario', [DashboardController::class, 'planoDiario'])->name('plano-diario');
    Route::post('/plano-diario/gerar', [DashboardController::class, 'gerarPlanoDiario'])->name('plano-diario.gerar');
    Route::post('/plano-diario/salvar', [DashboardController::class, 'salvarPlanoDiario'])->name('plano-diario.salvar');
    Route::delete('/plano-diario/limpar', [DashboardController::class, 'limparPlanoDiario'])->name('plano-diario.limpar');
    Route::patch('/tarefa/{tarefa}/pomodoro', [DashboardController::class, 'registrarPomodoro'])->name('tarefa.pomodoro');
    
    // CRUD de projetos para colaboradores
    Route::get('/projetos', [DashboardController::class, 'listarProjetos'])->name('projetos.index');
    Route::get('/projetos/criar', [DashboardController::class, 'criarProjeto'])->name('projetos.criar');
    Route::post('/projetos/criar', [DashboardController::class, 'armazenarProjeto'])->name('projetos.store');
    Route::get('/projetos/{projeto}', [DashboardController::class, 'verProjeto'])->name('projetos.show');
    Route::get('/projetos/{projeto}/editar', [DashboardController::class, 'editarProjeto'])->name('projetos.edit');
    Route::put('/projetos/{projeto}', [DashboardController::class, 'atualizarProjeto'])->name('projetos.update');
    
    // Google Calendar OAuth routes
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.auth');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
    Route::post('/auth/google/disconnect', [GoogleAuthController::class, 'disconnect'])->name('google.disconnect');
    
    // Agente Skala routes for colaboradores
    Route::get('/agente-skala', [AgenteSkalaController::class, 'index'])->name('agente-skala.index');
    Route::get('/agente-skala/{id}', [AgenteSkalaController::class, 'show'])->name('agente-skala.show');
    Route::patch('/agente-skala/plan/{planId}/status', [AgenteSkalaController::class, 'updatePlanStatus'])->name('agente-skala.plan.status');
});

// Rotas do Cliente (protegidas)
Route::prefix('cliente')->name('cliente.')->middleware(['web', App\Http\Middleware\ClienteAuth::class])->group(function () {
    Route::get('/dashboard', [ClienteDashboardController::class, 'index'])->name('dashboard');
    Route::get('/criar-task', [ClienteDashboardController::class, 'criarTask'])->name('criar-task');
    Route::post('/criar-task', [ClienteDashboardController::class, 'armazenarTask'])->name('armazenar-task');
    Route::get('/minhas-tasks', [ClienteDashboardController::class, 'minhasTasks'])->name('minhas-tasks');
    Route::get('/task/{id}', [ClienteDashboardController::class, 'verTask'])->name('task.detalhes');
});

// Rotas de autenticação do admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Rotas do Admin (protegidas)
Route::prefix('admin')->name('admin.')->middleware(['web', App\Http\Middleware\AdminAuth::class])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('colaboradores', ColaboradorController::class)->parameters([
        'colaboradores' => 'colaborador'
    ]);
    Route::resource('setores', SetorController::class)->parameters([
        'setores' => 'setor'
    ]);
    Route::resource('conhecimentos', ConhecimentoController::class);
    Route::resource('status-projetos', StatusProjetoController::class)->parameters([
        'status-projetos' => 'statusProjeto'
    ]);
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
    
    // Agente Skala routes
    Route::get('agente-skala', [AdminAgenteSkalaController::class, 'index'])->name('agente-skala.index');
    Route::get('agente-skala/{id}', [AdminAgenteSkalaController::class, 'show'])->name('agente-skala.show');
    Route::patch('agente-skala/plan/{planId}/status', [AdminAgenteSkalaController::class, 'updatePlanStatus'])->name('agente-skala.plan.status');
});
