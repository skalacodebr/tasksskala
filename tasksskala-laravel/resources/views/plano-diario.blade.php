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
                    <p class="text-gray-400 mt-1">Organize suas tarefas e acompanhe seu progresso durante o dia</p>
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
                    <p class="text-2xl font-bold text-white">{{ $tarefasPendentes }}</p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-900 bg-opacity-20 border border-yellow-700 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-400">Em Andamento</p>
                    <p class="text-2xl font-bold text-white">{{ $tarefasEmAndamento }}</p>
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
                    <p class="text-2xl font-bold text-white">{{ $tarefasAtrasadas }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Checklist do Dia -->
    @if($tarefasPlanejadasHoje->count() > 0)
    <div class="card-dark shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-primary-dark">
                    <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Checklist de Hoje
                </h3>
                <div class="text-sm text-muted-dark">
                    <span id="tarefas-concluidas">{{ $tarefasPlanejadasHoje->where('checklist_concluida', true)->count() }}</span> de {{ $tarefasPlanejadasHoje->count() }} concluídas
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-gray-700 rounded-full h-2 mb-6">
                <div class="bg-green-600 h-2 rounded-full transition-all duration-300" 
                     id="progress-bar"
                     style="width: {{ $tarefasPlanejadasHoje->count() > 0 ? ($tarefasPlanejadasHoje->where('checklist_concluida', true)->count() / $tarefasPlanejadasHoje->count() * 100) : 0 }}%">
                </div>
            </div>

            <!-- Lista de Tarefas como Checklist -->
            <div class="space-y-3">
                @foreach($tarefasPlanejadasHoje as $tarefa)
                <div class="checklist-item border border-gray-700 rounded-lg p-4 transition-all duration-200 {{ $tarefa->checklist_concluida ? 'bg-green-900 bg-opacity-20 border-green-700' : 'hover:bg-gray-800' }}"
                     data-tarefa-id="{{ $tarefa->id }}">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" 
                                   id="check-{{ $tarefa->id }}"
                                   class="w-5 h-5 text-green-600 bg-gray-700 border-gray-600 rounded focus:ring-green-500 focus:ring-2"
                                   {{ $tarefa->checklist_concluida ? 'checked' : '' }}
                                   onchange="toggleTarefa({{ $tarefa->id }})">
                        </div>
                        <div class="ml-3 flex-1">
                            <label for="check-{{ $tarefa->id }}" class="cursor-pointer">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-base font-medium {{ $tarefa->checklist_concluida ? 'text-gray-500 line-through' : 'text-white' }}">
                                            {{ $tarefa->titulo }}
                                        </h4>
                                        @if($tarefa->projeto)
                                        <p class="text-sm text-gray-400 mt-1">
                                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                            </svg>
                                            {{ $tarefa->projeto->nome }}
                                        </p>
                                        @endif
                                    </div>
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full
                                        @if($tarefa->prioridade == 'urgente') bg-red-900 text-red-300
                                        @elseif($tarefa->prioridade == 'alta') bg-orange-900 text-orange-300
                                        @elseif($tarefa->prioridade == 'media') bg-yellow-900 text-yellow-300
                                        @else bg-gray-700 text-gray-300
                                        @endif">
                                        {{ ucfirst($tarefa->prioridade) }}
                                    </span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Seleção de Tarefas para o Dia -->
    <div class="card-dark shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">
                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Adicionar Tarefas ao Plano de Hoje
            </h3>

            @if($tarefasDisponiveis->count() > 0)
            <form id="form-selecionar-tarefas" class="space-y-4">
                @csrf
                <!-- Filtros -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-muted-dark mb-1">Filtrar por Prioridade</label>
                        <select id="filtro-prioridade" class="w-full input-dark rounded-md">
                            <option value="">Todas</option>
                            <option value="urgente">Urgente</option>
                            <option value="alta">Alta</option>
                            <option value="media">Média</option>
                            <option value="baixa">Baixa</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted-dark mb-1">Filtrar por Projeto</label>
                        <select id="filtro-projeto" class="w-full input-dark rounded-md">
                            <option value="">Todos</option>
                            @foreach($tarefasDisponiveis->pluck('projeto')->unique('id')->filter() as $projeto)
                            <option value="{{ $projeto->id }}">{{ $projeto->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted-dark mb-1">Buscar</label>
                        <input type="text" id="busca-tarefa" placeholder="Digite para buscar..." 
                               class="w-full input-dark rounded-md">
                    </div>
                </div>

                <!-- Lista de Tarefas Disponíveis -->
                <div class="max-h-96 overflow-y-auto space-y-2 border border-gray-700 rounded-lg p-3">
                    @foreach($tarefasDisponiveis as $tarefa)
                    @if(!$tarefasPlanejadasHoje->contains('id', $tarefa->id))
                    <div class="tarefa-disponivel border border-gray-700 rounded p-3 hover:bg-gray-800 transition-colors"
                         data-prioridade="{{ $tarefa->prioridade }}"
                         data-projeto="{{ $tarefa->projeto_id }}"
                         data-titulo="{{ strtolower($tarefa->titulo) }}">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" 
                                   name="tarefas_selecionadas[]" 
                                   value="{{ $tarefa->id }}"
                                   class="mt-1 w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                            <div class="ml-3 flex-1">
                                <div class="font-medium text-white">{{ $tarefa->titulo }}</div>
                                @if($tarefa->descricao)
                                <div class="text-sm text-gray-400 mt-1">{{ Str::limit($tarefa->descricao, 100) }}</div>
                                @endif
                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                    @if($tarefa->projeto)
                                    <span>
                                        <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                        </svg>
                                        {{ $tarefa->projeto->nome }}
                                    </span>
                                    @endif
                                    @if($tarefa->data_vencimento)
                                    <span class="{{ $tarefa->data_vencimento < now() ? 'text-red-400' : '' }}">
                                        <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $tarefa->data_vencimento->format('d/m') }}
                                    </span>
                                    @endif
                                    <span class="px-2 py-0.5 text-xs rounded-full
                                        @if($tarefa->prioridade == 'urgente') bg-red-900 text-red-300
                                        @elseif($tarefa->prioridade == 'alta') bg-orange-900 text-orange-300
                                        @elseif($tarefa->prioridade == 'media') bg-yellow-900 text-yellow-300
                                        @else bg-gray-700 text-gray-300
                                        @endif">
                                        {{ ucfirst($tarefa->prioridade) }}
                                    </span>
                                </div>
                            </div>
                        </label>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div class="flex justify-between items-center mt-4">
                    <p class="text-sm text-gray-400">
                        <span id="contador-selecionadas">0</span> tarefas selecionadas
                    </p>
                    <button type="submit" class="btn-primary-dark px-6 py-2 rounded-lg transition-colors">
                        Adicionar ao Plano de Hoje
                    </button>
                </div>
            </form>
            @else
            <p class="text-gray-400 text-center py-8">Não há tarefas disponíveis para adicionar ao plano.</p>
            @endif
        </div>
    </div>
</div>

<script>
// Toggle de tarefa no checklist
function toggleTarefa(tarefaId) {
    fetch(`/tarefa/${tarefaId}/toggle-checklist`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`[data-tarefa-id="${tarefaId}"]`);
            const checkbox = document.getElementById(`check-${tarefaId}`);
            const titulo = item.querySelector('h4');
            
            if (data.concluida) {
                item.classList.add('bg-green-900', 'bg-opacity-20', 'border-green-700');
                titulo.classList.add('text-gray-500', 'line-through');
                titulo.classList.remove('text-white');
            } else {
                item.classList.remove('bg-green-900', 'bg-opacity-20', 'border-green-700');
                titulo.classList.remove('text-gray-500', 'line-through');
                titulo.classList.add('text-white');
            }
            
            // Atualizar contador e progress bar
            atualizarProgresso();
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao atualizar tarefa');
    });
}

// Atualizar progresso
function atualizarProgresso() {
    const total = document.querySelectorAll('.checklist-item').length;
    const concluidas = document.querySelectorAll('.checklist-item input:checked').length;
    const porcentagem = total > 0 ? (concluidas / total * 100) : 0;
    
    document.getElementById('tarefas-concluidas').textContent = concluidas;
    document.getElementById('progress-bar').style.width = porcentagem + '%';
}

// Filtros e busca
document.getElementById('filtro-prioridade')?.addEventListener('change', filtrarTarefas);
document.getElementById('filtro-projeto')?.addEventListener('change', filtrarTarefas);
document.getElementById('busca-tarefa')?.addEventListener('input', filtrarTarefas);

function filtrarTarefas() {
    const prioridade = document.getElementById('filtro-prioridade').value;
    const projeto = document.getElementById('filtro-projeto').value;
    const busca = document.getElementById('busca-tarefa').value.toLowerCase();
    
    document.querySelectorAll('.tarefa-disponivel').forEach(tarefa => {
        let mostrar = true;
        
        if (prioridade && tarefa.dataset.prioridade !== prioridade) {
            mostrar = false;
        }
        
        if (projeto && tarefa.dataset.projeto !== projeto) {
            mostrar = false;
        }
        
        if (busca && !tarefa.dataset.titulo.includes(busca)) {
            mostrar = false;
        }
        
        tarefa.style.display = mostrar ? 'block' : 'none';
    });
}

// Contador de tarefas selecionadas
document.querySelectorAll('input[name="tarefas_selecionadas[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const selecionadas = document.querySelectorAll('input[name="tarefas_selecionadas[]"]:checked').length;
        document.getElementById('contador-selecionadas').textContent = selecionadas;
    });
});

// Enviar formulário
document.getElementById('form-selecionar-tarefas')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("plano-diario.salvar") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Erro ao salvar plano');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao processar requisição');
    });
});
</script>
@endsection