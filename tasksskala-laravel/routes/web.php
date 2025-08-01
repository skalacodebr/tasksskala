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
use App\Http\Controllers\Admin\TutorialController;
use App\Http\Controllers\Auth\ColaboradorAuthController;
use App\Http\Controllers\Auth\ClienteAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AgenteSkalaController;
use App\Http\Controllers\Cliente\ClienteDashboardController;
use App\Http\Controllers\Cliente\TicketController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\ContaBancariaController;
use App\Http\Controllers\Admin\ContaPagarController;
use App\Http\Controllers\Admin\ContaReceberController;
use App\Http\Controllers\Admin\FluxoCaixaController;
use App\Http\Controllers\Admin\CategoriaFinanceiraController;
use App\Http\Controllers\Admin\DashboardFinanceiraController;
use App\Http\Controllers\Admin\ImportacaoOfxController;
use App\Http\Controllers\Admin\TipoCustoController;
use App\Http\Controllers\Admin\FornecedorController;

// Rota principal - redireciona para dashboard se logado, senão para login
Route::get('/', function () {
    if (session('colaborador_id')) {
        return redirect('/dashboard');
    }
    return redirect('/login');
})->name('home');

// Rotas para cronjob (sem autenticação)
Route::get('/cron/run-scheduler', [App\Http\Controllers\CronController::class, 'runScheduler'])
    ->name('cron.scheduler')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/cron/daily-report', [App\Http\Controllers\CronController::class, 'runDailyReport'])
    ->name('cron.daily-report')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Rotas de autenticação do colaborador
Route::get('/login', [ColaboradorAuthController::class, 'showLoginForm'])->name('colaborador.login.form');
Route::post('/login', [ColaboradorAuthController::class, 'login'])->name('colaborador.login');
Route::post('/logout', [ColaboradorAuthController::class, 'logout'])->name('colaborador.logout');

// Rotas de autenticação do cliente
Route::get('/cliente/login', [ClienteAuthController::class, 'showLoginForm'])->name('cliente.login.form');
Route::post('/cliente/login', [ClienteAuthController::class, 'login'])->name('cliente.login');
Route::post('/cliente/logout', [ClienteAuthController::class, 'logout'])->name('cliente.logout');

// Rotas do colaborador (dashboard)
Route::middleware(['web', App\Http\Middleware\ColaboradorAuth::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/minhas-tarefas', [DashboardController::class, 'minhasTarefas'])->name('minhas-tarefas');
    Route::get('/tarefa/criar', [DashboardController::class, 'criarTarefa'])->name('tarefa.criar');
    Route::post('/tarefa/criar', [DashboardController::class, 'armazenarTarefa'])->name('tarefa.store');
    Route::post('/tarefa/processar-ia', [DashboardController::class, 'processarTarefaIA'])->name('tarefa.processar-ia');
    Route::get('/tarefa/testar-api', [DashboardController::class, 'testarApiKey'])->name('tarefa.testar-api');
    Route::patch('/tarefa/{tarefa}/iniciar', [DashboardController::class, 'iniciarTarefa'])->name('tarefa.iniciar');
    Route::patch('/tarefa/{tarefa}/concluir', [DashboardController::class, 'concluirTarefa'])->name('tarefa.concluir');
    Route::patch('/tarefa/{tarefa}/pausar', [DashboardController::class, 'pausarTarefa'])->name('tarefa.pausar');
    Route::patch('/tarefa/{tarefa}/continuar', [DashboardController::class, 'continuarTarefa'])->name('tarefa.continuar');
    Route::post('/tarefa/{tarefa}/nota', [DashboardController::class, 'adicionarNota'])->name('tarefa.nota');
    Route::get('/tarefa/{tarefa}/detalhes', [DashboardController::class, 'verTarefa'])->name('tarefa.detalhes');
    Route::post('/tarefa/{tarefa}/transferir', [DashboardController::class, 'transferirTarefa'])->name('tarefa.transferir');
    
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
    
    // Tickets routes for colaboradores
    Route::get('/tickets', [App\Http\Controllers\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [App\Http\Controllers\TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/atribuir', [App\Http\Controllers\TicketController::class, 'atribuir'])->name('tickets.atribuir');
    Route::post('/tickets/{ticket}/responder', [App\Http\Controllers\TicketController::class, 'responder'])->name('tickets.responder');
    Route::patch('/tickets/{ticket}/status', [App\Http\Controllers\TicketController::class, 'alterarStatus'])->name('tickets.status');
    Route::post('/tickets/{ticket}/transferir', [App\Http\Controllers\TicketController::class, 'transferir'])->name('tickets.transferir');
    
    // Tutoriais para colaboradores
    Route::get('/tutoriais', [DashboardController::class, 'tutoriais'])->name('tutoriais');
    
    // Tarefas designadas
    Route::get('/tarefas-designadas', [DashboardController::class, 'tarefasDesignadas'])->name('tarefas-designadas');
    
    // Todas as tarefas (apenas para Administrativo)
    Route::get('/todas-tarefas', [DashboardController::class, 'todasTarefas'])->name('todas-tarefas');
    
    // Exportar tarefas
    Route::get('/tarefas/exportar', [DashboardController::class, 'exportarTarefas'])->name('tarefas.exportar');
    
    // Desempenho do Time
    Route::get('/desempenho-time', [DashboardController::class, 'desempenhoTime'])->name('desempenho-time');
    
    // Kanban de Vendas (apenas para Vendas e Administrativo)
    Route::prefix('kanban-vendas')->name('kanban-vendas.')->group(function () {
        Route::get('/', [App\Http\Controllers\KanbanVendasController::class, 'index'])->name('index');
        Route::post('/colunas', [App\Http\Controllers\KanbanVendasController::class, 'storeColuna'])->name('colunas.store');
        Route::put('/colunas/{coluna}', [App\Http\Controllers\KanbanVendasController::class, 'updateColuna'])->name('colunas.update');
        Route::delete('/colunas/{coluna}', [App\Http\Controllers\KanbanVendasController::class, 'deleteColuna'])->name('colunas.destroy');
        Route::post('/cards', [App\Http\Controllers\KanbanVendasController::class, 'storeCard'])->name('cards.store');
        Route::get('/cards/{card}', [App\Http\Controllers\KanbanVendasController::class, 'showCard'])->name('cards.show');
        Route::put('/cards/{card}', [App\Http\Controllers\KanbanVendasController::class, 'updateCard'])->name('cards.update');
        Route::delete('/cards/{card}', [App\Http\Controllers\KanbanVendasController::class, 'deleteCard'])->name('cards.destroy');
        Route::post('/cards/{card}/mover', [App\Http\Controllers\KanbanVendasController::class, 'moveCard'])->name('cards.move');
        Route::post('/colunas/reordenar', [App\Http\Controllers\KanbanVendasController::class, 'reorderColunas'])->name('colunas.reorder');
    });
    
    // Área de Reuniões
    Route::get('/reunioes', [App\Http\Controllers\ReuniaoController::class, 'index'])->name('reunioes.index');
    Route::post('/reunioes/analise-requisitos/gerar', [App\Http\Controllers\ReuniaoController::class, 'gerarAnaliseRequisitos'])->name('reunioes.analise-requisitos.gerar');
    Route::post('/reunioes/vincular-requisito', [App\Http\Controllers\ReuniaoController::class, 'vincularRequisito'])->name('reunioes.vincular-requisito');
    
    // Rotas WhatsApp Instances (apenas para Administrativo)
    Route::prefix('whatsapp-instances')->name('whatsapp-instances.')->group(function () {
        Route::get('/', [App\Http\Controllers\WhatsAppInstanceController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\WhatsAppInstanceController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\WhatsAppInstanceController::class, 'store'])->name('store');
        Route::get('/{instanceName}', [App\Http\Controllers\WhatsAppInstanceController::class, 'show'])->name('show');
        Route::delete('/{instanceName}', [App\Http\Controllers\WhatsAppInstanceController::class, 'destroy'])->name('destroy');
        Route::post('/{instanceName}/disconnect', [App\Http\Controllers\WhatsAppInstanceController::class, 'disconnect'])->name('disconnect');
    });
    
    // Rotas Financeiras para colaboradores (Administrativo e Financeiro)
    Route::prefix('financeiro')->name('financeiro.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardFinanceiraController::class, 'index'])->name('dashboard');
        Route::get('/fluxo-caixa', [App\Http\Controllers\Admin\FluxoCaixaController::class, 'index'])->name('fluxo-caixa');
        Route::get('/tipos-custo', [App\Http\Controllers\Admin\TipoCustoController::class, 'index'])->name('tipos-custo');
        Route::get('/categorias', [App\Http\Controllers\Admin\CategoriaFinanceiraController::class, 'index'])->name('categorias');
        Route::get('/contas-bancarias', [App\Http\Controllers\Admin\ContaBancariaController::class, 'index'])->name('contas-bancarias');
        Route::get('/contas-pagar', [App\Http\Controllers\Admin\ContaPagarController::class, 'index'])->name('contas-pagar');
        Route::get('/contas-receber', [App\Http\Controllers\Admin\ContaReceberController::class, 'index'])->name('contas-receber');
        Route::get('/fornecedores', [App\Http\Controllers\Admin\FornecedorController::class, 'index'])->name('fornecedores');
        Route::get('/importacao-ofx', [App\Http\Controllers\Admin\ImportacaoOfxController::class, 'index'])->name('importacao-ofx');
    });
});

// Rotas do Cliente (protegidas)
Route::prefix('cliente')->name('cliente.')->middleware(['web', App\Http\Middleware\ClienteAuth::class])->group(function () {
    Route::get('/dashboard', [ClienteDashboardController::class, 'index'])->name('dashboard');
    Route::get('/criar-task', [ClienteDashboardController::class, 'criarTask'])->name('criar-task');
    Route::post('/criar-task', [ClienteDashboardController::class, 'armazenarTask'])->name('armazenar-task');
    Route::get('/minhas-tasks', [ClienteDashboardController::class, 'minhasTasks'])->name('minhas-tasks');
    Route::get('/task/{id}', [ClienteDashboardController::class, 'verTask'])->name('task.detalhes');
    Route::get('/projetos', [ClienteDashboardController::class, 'meusProjetos'])->name('projetos');
    Route::get('/projeto/{projeto}', [ClienteDashboardController::class, 'verProjeto'])->name('projeto.detalhes');
    Route::get('/tutoriais', [ClienteDashboardController::class, 'tutoriais'])->name('tutoriais');
    
    // Rotas de Feedback
    Route::get('/feedbacks', [ClienteDashboardController::class, 'feedbacks'])->name('feedbacks');
    Route::get('/feedback/criar', [ClienteDashboardController::class, 'criarFeedback'])->name('feedback.criar');
    Route::post('/feedback', [ClienteDashboardController::class, 'armazenarFeedback'])->name('feedback.armazenar');
    Route::get('/feedback/{feedback}', [ClienteDashboardController::class, 'verFeedback'])->name('feedback.show');
    Route::post('/feedback/{feedback}/avaliar', [ClienteDashboardController::class, 'avaliarFeedback'])->name('feedback.avaliar');
    
    // Rotas de tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::patch('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
});

// Rota pública para teste do agente SRS
Route::get('/teste_agente', [App\Http\Controllers\AgenteSRSController::class, 'index'])->name('agente-srs.index');
Route::post('/teste_agente/enriquecer-descricao', [App\Http\Controllers\AgenteSRSController::class, 'enriquecerDescricao'])->name('agente-srs.enriquecer-descricao');
Route::post('/teste_agente/gerar-perguntas', [App\Http\Controllers\AgenteSRSController::class, 'gerarPerguntas'])->name('agente-srs.gerar-perguntas');
Route::post('/teste_agente/gerar-srs', [App\Http\Controllers\AgenteSRSController::class, 'gerarSRS'])->name('agente-srs.gerar-srs');
Route::get('/teste_agente/download-srs', [App\Http\Controllers\AgenteSRSController::class, 'downloadSRS'])->name('agente-srs.download');

// Rota pública para teste do agente SRS v2 (perguntas fixas)
Route::get('/teste_agente2', [App\Http\Controllers\AgenteSRS2Controller::class, 'index'])->name('agente-srs2.index');
Route::post('/teste_agente2/gerar-srs', [App\Http\Controllers\AgenteSRS2Controller::class, 'gerarSRS'])->name('agente-srs2.gerar-srs');
Route::post('/teste_agente2/sugerir-resposta', [App\Http\Controllers\AgenteSRS2Controller::class, 'sugerirResposta'])->name('agente-srs2.sugerir');
Route::get('/teste_agente2/download-srs', [App\Http\Controllers\AgenteSRS2Controller::class, 'downloadSRS'])->name('agente-srs2.download');
Route::get('/teste_agente2/historico', [App\Http\Controllers\AgenteSRS2Controller::class, 'historico'])->name('agente-srs2.historico');
Route::get('/teste_agente2/historico/{id}', [App\Http\Controllers\AgenteSRS2Controller::class, 'verHistorico'])->name('agente-srs2.ver-historico');

// Rota pública para o agente de chat
Route::get('/agente_chat', [App\Http\Controllers\ChatController::class, 'index'])->name('agente-chat.index');
Route::post('/agente_chat/start', [App\Http\Controllers\ChatController::class, 'start'])->name('agente-chat.start');
Route::post('/agente_chat/send', [App\Http\Controllers\ChatController::class, 'send'])->name('agente-chat.send');

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
    Route::post('tarefas/{tarefa}/transferir', [TarefaController::class, 'transferir'])->name('tarefas.transferir');
    
    // Agente Skala routes
    Route::get('agente-skala', [AdminAgenteSkalaController::class, 'index'])->name('agente-skala.index');
    Route::get('agente-skala/{id}', [AdminAgenteSkalaController::class, 'show'])->name('agente-skala.show');
    Route::patch('agente-skala/plan/{planId}/status', [AdminAgenteSkalaController::class, 'updatePlanStatus'])->name('agente-skala.plan.status');
    Route::patch('agente-skala/{taskId}/status', [AdminAgenteSkalaController::class, 'updateTaskStatus'])->name('agente-skala.task.status');
    
    // Tutoriais resource routes
    Route::resource('tutoriais', TutorialController::class)->parameters([
        'tutoriais' => 'tutorial'
    ]);
    
    // Feedbacks routes
    Route::get('feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');
    Route::get('feedbacks/estatisticas', [FeedbackController::class, 'estatisticas'])->name('feedbacks.estatisticas');
    Route::get('feedbacks/{feedback}', [FeedbackController::class, 'show'])->name('feedbacks.show');
    Route::post('feedbacks/{feedback}/responder', [FeedbackController::class, 'responder'])->name('feedbacks.responder');
    Route::post('feedbacks/{feedback}/status', [FeedbackController::class, 'atualizarStatus'])->name('feedbacks.atualizarStatus');
    Route::delete('feedbacks/{feedback}', [FeedbackController::class, 'destroy'])->name('feedbacks.destroy');
    
    // Rotas do Sistema Financeiro
    Route::get('dashboard-financeira', [DashboardFinanceiraController::class, 'index'])->name('dashboard-financeira.index');
    Route::get('fluxo-caixa', [FluxoCaixaController::class, 'index'])->name('fluxo-caixa.index');
    
    Route::resource('tipos-custo', TipoCustoController::class)->parameters([
        'tipos-custo' => 'tipoCusto'
    ]);
    
    Route::resource('categorias-financeiras', CategoriaFinanceiraController::class)->parameters([
        'categorias-financeiras' => 'categoria_financeira'
    ]);
    
    Route::resource('contas-bancarias', ContaBancariaController::class)->parameters([
        'contas-bancarias' => 'conta_bancaria'
    ]);
    
    Route::resource('fornecedores', FornecedorController::class)->parameters([
        'fornecedores' => 'fornecedor'
    ]);
    
    Route::resource('contas-pagar', ContaPagarController::class)->parameters([
        'contas-pagar' => 'conta_pagar'
    ]);
    Route::post('contas-pagar/{conta_pagar}/pagar', [ContaPagarController::class, 'pagar'])->name('contas-pagar.pagar');
    
    Route::resource('contas-receber', ContaReceberController::class)->parameters([
        'contas-receber' => 'conta_receber'
    ]);
    Route::post('contas-receber/{conta_receber}/receber', [ContaReceberController::class, 'receber'])->name('contas-receber.receber');
    
    // Rotas de Importação OFX
    Route::get('importacao-ofx', [ImportacaoOfxController::class, 'index'])->name('importacao-ofx.index');
    Route::get('importacao-ofx/importar', [ImportacaoOfxController::class, 'create'])->name('importacao-ofx.create');
    Route::post('importacao-ofx/importar', [ImportacaoOfxController::class, 'store'])->name('importacao-ofx.store');
    Route::get('importacao-ofx/conciliar', [ImportacaoOfxController::class, 'conciliar'])->name('importacao-ofx.conciliar');
    Route::post('importacao-ofx/conciliar/{transacao}', [ImportacaoOfxController::class, 'conciliarTransacao'])->name('importacao-ofx.conciliar-transacao');
    Route::get('importacao-ofx/buscar-contas/{transacao}', [ImportacaoOfxController::class, 'buscarContasSugeridas'])->name('importacao-ofx.buscar-contas');
    
});
