@extends('layouts.colaborador')

@section('title', 'Plano de Ação Diário')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="card-dark shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-primary-dark">Plano de Ação Diário</h1>
                    <p class="text-gray-400 mt-1">Organize suas tarefas com foco e produtividade usando a técnica Pomodoro</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-muted-dark">{{ now()->format('d/m/Y') }}</div>
                    <div class="font-medium text-primary-dark">{{ now()->locale('pt')->translatedFormat('l') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-900 bg-opacity-20 border border-blue-700 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-400">Tarefas Pendentes</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $tarefasPendentes }}</p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-900 bg-opacity-20 border border-yellow-700 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-600">Em Andamento</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $tarefasEmAndamento }}</p>
                </div>
            </div>
        </div>

        <div class="bg-red-900 bg-opacity-20 border border-red-700 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-400">Atrasadas</p>
                    <p class="text-2xl font-bold text-red-900">{{ $tarefasAtrasadas }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuração do Plano -->
    <div class="card-dark shadow rounded-lg" id="configuracao-plano">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">
                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Configurar Plano de Hoje
            </h3>
            
            <form id="form-gerar-plano" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="horas_trabalho" class="block text-sm font-medium text-muted-dark">
                            Horas de Trabalho
                        </label>
                        <select name="horas_trabalho" id="horas_trabalho" required
                                class="mt-1 block w-full input-dark rounded-md shadow-sm">
                            <option value="4">4 horas</option>
                            <option value="6">6 horas</option>
                            <option value="8" selected>8 horas</option>
                            <option value="10">10 horas</option>
                        </select>
                    </div>

                    <div>
                        <label for="tempo_pomodoro" class="block text-sm font-medium text-muted-dark">
                            Tempo Pomodoro
                        </label>
                        <select name="tempo_pomodoro" id="tempo_pomodoro" required
                                class="mt-1 block w-full input-dark rounded-md shadow-sm">
                            <option value="25" selected>25 minutos</option>
                            <option value="30">30 minutos</option>
                            <option value="45">45 minutos</option>
                            <option value="60">60 minutos</option>
                        </select>
                    </div>

                    <div>
                        <label for="prioridade_minima" class="block text-sm font-medium text-muted-dark">
                            Prioridade Mínima
                        </label>
                        <select name="prioridade_minima" id="prioridade_minima" required
                                class="mt-1 block w-full input-dark rounded-md shadow-sm">
                            <option value="baixa">Baixa</option>
                            <option value="media" selected>Média</option>
                            <option value="alta">Alta</option>
                            <option value="urgente">Urgente</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <label class="flex items-center">
                            <input type="checkbox" name="incluir_atrasadas" value="1" checked
                                   class="rounded border-gray-600 text-blue-400 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-muted-dark">Incluir atrasadas</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button type="submit" 
                            class="btn-primary-dark font-bold py-3 px-6 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        Gerar Plano Inteligente
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultado do Plano -->
    <div id="resultado-plano" class="hidden space-y-6">
        <!-- Plano do Dia -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-700 rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg leading-6 font-medium text-primary-dark">
                        🎯 Plano para Hoje
                    </h3>
                    <div class="text-right text-sm text-gray-400">
                        <div>Tempo total: <span id="tempo-total" class="font-medium"></span></div>
                        <div>Pomodoros: <span id="pomodoros-necessarios" class="font-medium"></span></div>
                    </div>
                </div>

                <div id="tarefas-dia" class="space-y-3">
                    <!-- Tarefas do dia serão inseridas aqui -->
                </div>

                <div class="mt-6 flex justify-center space-x-3">
                    <button id="btn-salvar-plano" onclick="salvarPlano()" 
                            class="btn-primary-dark font-bold py-2 px-6 rounded-lg">
                        💾 Salvar e Iniciar Plano
                    </button>
                    <button id="btn-regerar-plano" onclick="mostrarConfiguracaoPlano()" 
                            class="btn-primary-dark font-bold py-2 px-6 rounded-lg hidden">
                        🔄 Gerar Novo Plano
                    </button>
                </div>
            </div>
        </div>

        <!-- Próximos Dias -->
        <div class="card-dark shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">
                    📅 Organização dos Próximos Dias
                </h3>
                <div id="proximos-dias" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Próximos dias serão inseridos aqui -->
                </div>
            </div>
        </div>
    </div>

    <!-- Timer Pomodoro -->
    <div id="pomodoro-timer" class="hidden card-dark shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="text-center">
                <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">
                    🍅 Timer Pomodoro
                </h3>
                
                <div class="mb-6">
                    <div id="timer-display" class="text-6xl font-bold text-primary-dark mb-2">25:00</div>
                    <div id="timer-status" class="text-lg text-gray-400">Pronto para iniciar</div>
                </div>

                <div class="space-x-4">
                    <button id="btn-iniciar" onclick="iniciarPomodoro()" 
                            class="btn-primary-dark font-bold py-2 px-4 rounded">
                        ▶️ Iniciar
                    </button>
                    <button id="btn-pausar" onclick="pausarPomodoro()" class="hidden bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        ⏸️ Pausar
                    </button>
                    <button id="btn-parar" onclick="pararPomodoro()" class="hidden bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        ⏹️ Parar
                    </button>
                </div>

                <div class="mt-4">
                    <select id="tipo-sessao" class="input-dark rounded-md shadow-sm">
                        <option value="25">Foco (25 min)</option>
                        <option value="5">Pausa Curta (5 min)</option>
                        <option value="15">Pausa Longa (15 min)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let planoAtual = null;
let timerInterval = null;
let tempoRestante = 25 * 60; // 25 minutos em segundos
let timerRodando = false;

// Gerar plano diário
document.getElementById('form-gerar-plano').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("plano-diario.gerar") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            planoAtual = data.plano;
            exibirPlano(data.plano);
        }
    } catch (error) {
        console.error('Erro ao gerar plano:', error);
        alert('Erro ao gerar plano. Tente novamente.');
    }
});

function exibirPlano(plano) {
    // Mostrar resultado
    document.getElementById('resultado-plano').classList.remove('hidden');
    document.getElementById('pomodoro-timer').classList.remove('hidden');
    
    // Atualizar informações do cabeçalho
    document.getElementById('tempo-total').textContent = Math.round(plano.tempo_total_estimado / 60) + 'h ' + (plano.tempo_total_estimado % 60) + 'min';
    document.getElementById('pomodoros-necessarios').textContent = plano.pomodoros_necessarios;
    
    // Exibir tarefas do dia
    const containerTarefasDia = document.getElementById('tarefas-dia');
    containerTarefasDia.innerHTML = '';
    
    plano.tarefas_dia.forEach((tarefa, index) => {
        const tarefaDiv = document.createElement('div');
        tarefaDiv.className = 'flex items-center p-3 card-dark border border-gray-700 rounded-lg';
        tarefaDiv.innerHTML = `
            <input type="checkbox" class="tarefa-selecionada mr-3" value="${tarefa.id}" checked>
            <div class="flex-1">
                <h4 class="font-medium text-primary-dark">${tarefa.titulo}</h4>
                <div class="text-sm text-gray-400">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-${getPrioridadeColor(tarefa.prioridade)}-800 text-${getPrioridadeColor(tarefa.prioridade)}-200 mr-2">
                        ${tarefa.prioridade.charAt(0).toUpperCase() + tarefa.prioridade.slice(1)}
                    </span>
                    Tempo estimado: ${Math.round(tarefa.tempo_estimado / 60)}h${tarefa.tempo_estimado % 60 ? ' ' + (tarefa.tempo_estimado % 60) + 'min' : ''}
                    ${tarefa.projeto ? ' • Projeto: ' + tarefa.projeto.nome : ''}
                </div>
                <div class="flex space-x-2 mt-2">
                    <a href="/tarefa/${tarefa.id}/detalhes" class="text-xs bg-blue-800 text-blue-200 px-2 py-1 rounded hover:bg-blue-200">Ver Detalhes</a>
                    ${tarefa.status === 'pendente' ? `
                        <button onclick="iniciarTarefa(${tarefa.id})" class="text-xs bg-green-800 text-green-200 px-2 py-1 rounded hover:bg-green-200">
                            ▶️ Iniciar
                        </button>
                    ` : ''}
                    ${tarefa.status === 'em_andamento' ? `
                        <button onclick="concluirTarefa(${tarefa.id})" class="text-xs bg-blue-800 text-blue-200 px-2 py-1 rounded hover:bg-blue-200">
                            ✅ Concluir
                        </button>
                    ` : ''}
                    ${tarefa.status === 'concluida' ? `
                        <span class="text-xs bg-green-800 text-green-200 px-2 py-1 rounded">
                            ✅ Concluída
                        </span>
                    ` : ''}
                </div>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-gray-400">${tarefa.pontuacao || 'N/A'}</div>
                <div class="text-xs text-muted-dark">pontos</div>
            </div>
        `;
        containerTarefasDia.appendChild(tarefaDiv);
    });
    
    // Exibir próximos dias
    const containerProximosDias = document.getElementById('proximos-dias');
    containerProximosDias.innerHTML = '';
    
    plano.proximos_dias.forEach(dia => {
        const diaDiv = document.createElement('div');
        diaDiv.className = 'bg-gray-800 p-3 rounded-lg';
        diaDiv.innerHTML = `
            <h4 class="font-medium text-primary-dark mb-2">${dia.dia_semana}</h4>
            <div class="text-xs text-muted-dark mb-2">${dia.data_formatada}</div>
            <div class="space-y-1">
                ${dia.tarefas.map(tarefa => `
                    <div class="text-sm text-muted-dark">${tarefa.titulo}</div>
                `).join('')}
            </div>
        `;
        containerProximosDias.appendChild(diaDiv);
    });
}

function getPrioridadeColor(prioridade) {
    const cores = {
        'baixa': 'green',
        'media': 'yellow',
        'alta': 'orange',
        'urgente': 'red'
    };
    return cores[prioridade] || 'gray';
}

async function salvarPlano() {
    const tarefasSelecionadas = Array.from(document.querySelectorAll('.tarefa-selecionada:checked')).map(cb => cb.value);
    
    if (tarefasSelecionadas.length === 0) {
        alert('Selecione pelo menos uma tarefa para o seu plano!');
        return;
    }
    
    // Desabilitar botão para evitar cliques duplos
    const btnSalvar = document.getElementById('btn-salvar-plano');
    btnSalvar.disabled = true;
    btnSalvar.textContent = '💾 Salvando...';
    
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    tarefasSelecionadas.forEach(id => formData.append('tarefas_selecionadas[]', id));
    
    try {
        const response = await fetch('{{ route("plano-diario.salvar") }}', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Mostrar mensagem de sucesso
            alert(data.message);
            
            // Mudar o botão para indicar que foi salvo
            btnSalvar.textContent = '✅ Plano Salvo';
            btnSalvar.className = 'bg-gray-8000 text-white font-bold py-2 px-6 rounded-lg cursor-not-allowed';
            
            // Esconder o formulário de configuração
            document.getElementById('configuracao-plano').style.display = 'none';
            
            // Atualizar o título da seção
            const tituloPlano = document.querySelector('#resultado-plano h3');
            if (tituloPlano) {
                tituloPlano.innerHTML = '🎯 Seu Plano de Hoje (Salvo)';
            }
            
            // Mostrar botão de regerar
            const btnRegerar = document.getElementById('btn-regerar-plano');
            if (btnRegerar) {
                btnRegerar.classList.remove('hidden');
            }
        } else {
            throw new Error(data.message || 'Erro ao salvar plano');
        }
    } catch (error) {
        console.error('Erro ao salvar plano:', error);
        alert('Erro ao salvar plano. Tente novamente.');
        
        // Reabilitar botão em caso de erro
        btnSalvar.disabled = false;
        btnSalvar.textContent = '💾 Salvar e Iniciar Plano';
    }
}

async function mostrarConfiguracaoPlano() {
    if (confirm('🔄 Tem certeza que deseja gerar um novo plano? Isso substituirá o plano atual.')) {
        try {
            // Limpar plano atual no servidor
            const response = await fetch('{{ route("plano-diario.limpar") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
            
            if (response.ok) {
                // Mostrar formulário de configuração
                document.getElementById('configuracao-plano').style.display = 'block';
                
                // Esconder resultado atual
                document.getElementById('resultado-plano').classList.add('hidden');
                
                // Esconder timer Pomodoro
                document.getElementById('pomodoro-timer').classList.add('hidden');
                
                // Limpar plano atual
                planoAtual = null;
                
                // Resetar formulário para valores padrão
                document.getElementById('horas_trabalho').value = '8';
                document.getElementById('tempo_pomodoro').value = '25';
                document.getElementById('prioridade_minima').value = 'media';
                document.querySelector('input[name="incluir_atrasadas"]').checked = true;
                
                // Scroll para o formulário
                document.getElementById('configuracao-plano').scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Mostrar mensagem de sucesso
                const data = await response.json();
                if (data.message) {
                    // Mostrar toast em vez de alert
                    mostrarToast(data.message, 'success');
                }
            } else {
                alert('Erro ao limpar plano atual. Tente novamente.');
            }
        } catch (error) {
            console.error('Erro ao limpar plano:', error);
            alert('Erro ao limpar plano atual. Tente novamente.');
        }
    }
}

function mostrarToast(message, type = 'success') {
    // Criar elemento de toast
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-900 bg-opacity-200 text-white' : 'bg-red-900 bg-opacity-200 text-white'
    }`;
    toast.textContent = message;
    
    // Adicionar ao DOM
    document.body.appendChild(toast);
    
    // Remover após 3 segundos
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 3000);
}

// Timer Pomodoro
function iniciarPomodoro() {
    const tipoSessao = document.getElementById('tipo-sessao').value;
    tempoRestante = parseInt(tipoSessao) * 60;
    
    timerRodando = true;
    document.getElementById('btn-iniciar').classList.add('hidden');
    document.getElementById('btn-pausar').classList.remove('hidden');
    document.getElementById('btn-parar').classList.remove('hidden');
    
    timerInterval = setInterval(() => {
        tempoRestante--;
        atualizarDisplay();
        
        if (tempoRestante <= 0) {
            finalizarPomodoro();
        }
    }, 1000);
    
    document.getElementById('timer-status').textContent = 'Em andamento...';
}

function pausarPomodoro() {
    timerRodando = false;
    clearInterval(timerInterval);
    
    document.getElementById('btn-iniciar').classList.remove('hidden');
    document.getElementById('btn-pausar').classList.add('hidden');
    document.getElementById('timer-status').textContent = 'Pausado';
}

function pararPomodoro() {
    timerRodando = false;
    clearInterval(timerInterval);
    tempoRestante = 25 * 60;
    
    document.getElementById('btn-iniciar').classList.remove('hidden');
    document.getElementById('btn-pausar').classList.add('hidden');
    document.getElementById('btn-parar').classList.add('hidden');
    
    atualizarDisplay();
    document.getElementById('timer-status').textContent = 'Pronto para iniciar';
}

function atualizarDisplay() {
    const minutos = Math.floor(tempoRestante / 60);
    const segundos = tempoRestante % 60;
    document.getElementById('timer-display').textContent = 
        `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
}

function finalizarPomodoro() {
    clearInterval(timerInterval);
    timerRodando = false;
    
    // Reproduzir som ou notificação
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification('Pomodoro concluído! 🍅', {
            body: 'Hora de fazer uma pausa!'
        });
    }
    
    alert('Pomodoro concluído! 🍅 Hora de fazer uma pausa!');
    pararPomodoro();
}

// Solicitar permissão para notificações
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}

// Verificar se já existe um plano salvo ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    @if($planoExistente)
        // Exibir plano existente
        planoAtual = @json($planoExistente);
        exibirPlanoExistente(planoAtual);
    @endif
});

function exibirPlanoExistente(plano) {
    // Mostrar seções do plano
    document.getElementById('resultado-plano').classList.remove('hidden');
    document.getElementById('pomodoro-timer').classList.remove('hidden');
    
    // Esconder formulário de configuração
    document.getElementById('configuracao-plano').style.display = 'none';
    
    // Atualizar informações do cabeçalho
    document.getElementById('tempo-total').textContent = Math.round(plano.tempo_total_estimado / 60) + 'h ' + (plano.tempo_total_estimado % 60) + 'min';
    document.getElementById('pomodoros-necessarios').textContent = plano.pomodoros_necessarios;
    
    // Exibir tarefas do dia
    const containerTarefasDia = document.getElementById('tarefas-dia');
    containerTarefasDia.innerHTML = '';
    
    plano.tarefas_dia.forEach((tarefa, index) => {
        const tarefaDiv = document.createElement('div');
        tarefaDiv.className = 'flex items-center p-3 card-dark border border-gray-700 rounded-lg';
        
        // Calcular tempo estimado se não estiver definido
        const tempoEstimado = tarefa.tempo_estimado || (function() {
            switch(tarefa.prioridade) {
                case 'baixa': return 60;
                case 'media': return 90;
                case 'alta': return 120;
                case 'urgente': return 180;
                default: return 90;
            }
        })();
        
        tarefaDiv.innerHTML = `
            <div class="w-6 h-6 bg-green-800 border-2 border-green-500 rounded mr-3 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="font-medium text-primary-dark">${tarefa.titulo}</h4>
                <div class="text-sm text-gray-400">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-${getPrioridadeColor(tarefa.prioridade)}-800 text-${getPrioridadeColor(tarefa.prioridade)}-200 mr-2">
                        ${tarefa.prioridade.charAt(0).toUpperCase() + tarefa.prioridade.slice(1)}
                    </span>
                    Tempo estimado: ${Math.round(tempoEstimado / 60)}h${tempoEstimado % 60 ? ' ' + (tempoEstimado % 60) + 'min' : ''}
                    ${tarefa.projeto ? ' • Projeto: ' + tarefa.projeto.nome : ''}
                </div>
                <div class="flex space-x-2 mt-2">
                    <a href="/tarefa/${tarefa.id}/detalhes" class="text-xs bg-blue-800 text-blue-200 px-2 py-1 rounded hover:bg-blue-200">Ver Detalhes</a>
                    ${tarefa.status === 'pendente' ? `
                        <button onclick="iniciarTarefa(${tarefa.id})" class="text-xs bg-green-800 text-green-200 px-2 py-1 rounded hover:bg-green-200">
                            ▶️ Iniciar
                        </button>
                    ` : ''}
                    ${tarefa.status === 'em_andamento' ? `
                        <button onclick="concluirTarefa(${tarefa.id})" class="text-xs bg-blue-800 text-blue-200 px-2 py-1 rounded hover:bg-blue-200">
                            ✅ Concluir
                        </button>
                    ` : ''}
                    ${tarefa.status === 'concluida' ? `
                        <span class="text-xs bg-green-800 text-green-200 px-2 py-1 rounded">
                            ✅ Concluída
                        </span>
                    ` : ''}
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm font-medium text-primary-dark">${tarefa.status === 'pendente' ? 'Pendente' : tarefa.status === 'em_andamento' ? 'Em Andamento' : 'Concluída'}</div>
            </div>
        `;
        containerTarefasDia.appendChild(tarefaDiv);
    });
    
    // Atualizar título
    const tituloPlano = document.querySelector('#resultado-plano h3');
    if (tituloPlano) {
        tituloPlano.innerHTML = '🎯 Seu Plano de Hoje (Salvo)';
    }
    
    // Exibir próximos dias se existirem
    if (plano.proximos_dias && plano.proximos_dias.length > 0) {
        const containerProximosDias = document.getElementById('proximos-dias');
        containerProximosDias.innerHTML = '';
        
        plano.proximos_dias.forEach(dia => {
            const diaDiv = document.createElement('div');
            diaDiv.className = 'bg-gray-800 p-3 rounded-lg';
            diaDiv.innerHTML = `
                <h4 class="font-medium text-primary-dark mb-2">${dia.dia_semana}</h4>
                <div class="text-xs text-muted-dark mb-2">${dia.data_formatada}</div>
                <div class="space-y-1">
                    ${dia.tarefas.map(tarefa => `
                        <div class="text-sm text-muted-dark p-2 card-dark rounded border">
                            <div class="font-medium">${tarefa.titulo}</div>
                            <div class="text-xs text-muted-dark">${tarefa.prioridade.charAt(0).toUpperCase() + tarefa.prioridade.slice(1)}</div>
                        </div>
                    `).join('')}
                    ${dia.tarefas.length === 0 ? '<div class="text-sm text-muted-dark italic">Nenhuma tarefa</div>' : ''}
                </div>
            `;
            containerProximosDias.appendChild(diaDiv);
        });
    } else {
        // Se não há próximos dias, esconder a seção
        const secaoProximosDias = document.getElementById('proximos-dias').closest('.card-dark');
        if (secaoProximosDias) {
            secaoProximosDias.style.display = 'none';
        }
    }
    
    // Atualizar botões
    const btnSalvar = document.getElementById('btn-salvar-plano');
    btnSalvar.textContent = '✅ Plano Salvo';
    btnSalvar.className = 'bg-gray-8000 text-white font-bold py-2 px-6 rounded-lg cursor-not-allowed';
    btnSalvar.disabled = true;
    
    // Mostrar botão de regerar
    const btnRegerar = document.getElementById('btn-regerar-plano');
    if (btnRegerar) {
        btnRegerar.classList.remove('hidden');
    }
}

async function iniciarTarefa(tarefaId) {
    try {
        const response = await fetch(`/tarefa/${tarefaId}/iniciar`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            location.reload(); // Recarregar para atualizar status
        } else {
            alert('Erro ao iniciar tarefa. Tente novamente.');
        }
    } catch (error) {
        console.error('Erro ao iniciar tarefa:', error);
        alert('Erro ao iniciar tarefa. Tente novamente.');
    }
}

async function concluirTarefa(tarefaId) {
    const observacoes = prompt('Observações sobre a conclusão da tarefa (opcional):');
    
    try {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('_method', 'PATCH');
        if (observacoes) {
            formData.append('observacoes', observacoes);
        }
        
        const response = await fetch(`/tarefa/${tarefaId}/concluir`, {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            location.reload(); // Recarregar para atualizar status
        } else {
            alert('Erro ao concluir tarefa. Tente novamente.');
        }
    } catch (error) {
        console.error('Erro ao concluir tarefa:', error);
        alert('Erro ao concluir tarefa. Tente novamente.');
    }
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection