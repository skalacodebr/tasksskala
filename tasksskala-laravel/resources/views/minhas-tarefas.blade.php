@extends('layouts.colaborador')

@section('title', 'Minhas Tarefas')

@section('content')
<div class="space-y-6">
    <!-- Filtros -->
    <div class="card-dark shadow rounded-lg p-4">
        <form method="GET" action="{{ route('minhas-tarefas') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-muted-dark">Status</label>
                <select name="status" class="mt-1 block w-full input-dark rounded-md shadow-sm">
                    <option value="">Todos</option>
                    <option value="pendente_em_andamento" {{ (!request()->hasAny(['status', 'prioridade', 'projeto_id']) || request('status') == 'pendente_em_andamento') ? 'selected' : '' }}>Pendente + Em Andamento</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="em_andamento" {{ request('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                    <option value="concluida" {{ request('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                    <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-muted-dark">Prioridade</label>
                <select name="prioridade" class="mt-1 block w-full input-dark rounded-md shadow-sm">
                    <option value="">Todas</option>
                    <option value="urgente" {{ request('prioridade') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                    <option value="alta" {{ request('prioridade') == 'alta' ? 'selected' : '' }}>Alta</option>
                    <option value="media" {{ request('prioridade') == 'media' ? 'selected' : '' }}>Média</option>
                    <option value="baixa" {{ request('prioridade') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-muted-dark">Projeto</label>
                <select name="projeto_id" class="mt-1 block w-full input-dark rounded-md shadow-sm">
                    <option value="">Todos</option>
                    @foreach($projetos as $projeto)
                        <option value="{{ $projeto->id }}" {{ request('projeto_id') == $projeto->id ? 'selected' : '' }}>
                            {{ $projeto->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3">
                <button type="submit" class="btn-primary-dark font-bold py-2 px-4 rounded">
                    Filtrar
                </button>
                <a href="{{ route('minhas-tarefas') }}" class="ml-2 btn-secondary-dark font-bold py-2 px-4 rounded">
                    Limpar
                </a>
                <button type="button" onclick="openExportModal()" class="ml-2 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Exportar Tarefas
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Tarefas -->
    <div class="card-dark shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-700">
            @forelse($tarefas as $tarefa)
            <li>
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-lg font-medium text-blue-400 truncate">
                                        {{ $tarefa->titulo }}
                                    </p>
                                    @if($tarefa->descricao)
                                        <p class="text-sm text-gray-400 mt-1">
                                            {{ Str::limit($tarefa->descricao, 100) }}
                                        </p>
                                    @endif
                                    <div class="mt-2 flex items-center space-x-4 text-sm text-muted-dark">
                                        @if($tarefa->projeto)
                                            <span>Projeto: {{ $tarefa->projeto->nome }}</span>
                                        @endif
                                        @if($tarefa->data_vencimento)
                                            <span>Vencimento: {{ $tarefa->data_vencimento->format('d/m/Y H:i') }}</span>
                                        @endif
                                        @if($tarefa->data_inicio && $tarefa->data_fim)
                                            <span>Duração: {{ $tarefa->duracao_formatada }}</span>
                                        @endif
                                    </div>
                                    <div class="mt-2 flex items-center space-x-2">
                                        @php
                                            $statusColors = [
                                                'pendente' => 'bg-gray-800 text-gray-800',
                                                'em_andamento' => 'bg-blue-800 text-blue-200',
                                                'concluida' => 'bg-green-800 text-green-200',
                                                'cancelada' => 'bg-red-800 text-red-200'
                                            ];
                                            $prioridadeColors = [
                                                'baixa' => 'bg-green-800 text-green-200',
                                                'media' => 'bg-yellow-800 text-yellow-200',
                                                'alta' => 'bg-orange-800 text-orange-200',
                                                'urgente' => 'bg-red-800 text-red-200'
                                            ];
                                            $tipoColors = [
                                                'manual' => 'bg-gray-800 text-gray-800',
                                                'automatica_feedback' => 'bg-purple-800 text-purple-200',
                                                'automatica_aprovacao' => 'bg-indigo-800 text-indigo-200'
                                            ];
                                        @endphp
                                        
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$tarefa->status] ?? 'bg-gray-800 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $tarefa->status)) }}
                                        </span>
                                        
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioridadeColors[$tarefa->prioridade] ?? 'bg-gray-800 text-gray-800' }}">
                                            {{ ucfirst($tarefa->prioridade) }}
                                        </span>
                                        
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tipoColors[$tarefa->tipo] ?? 'bg-gray-800 text-gray-800' }}">
                                            {{ str_replace(['automatica_', '_'], ['Auto ', ' '], ucfirst($tarefa->tipo)) }}
                                        </span>

                                        @if($tarefa->recorrente)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-800 text-blue-200">
                                                Recorrente
                                            </span>
                                        @endif
                                        
                                        @if($tarefa->pausada)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-800 text-yellow-200">
                                                Pausada
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <!-- Botões de Ação -->
                            @if($tarefa->status == 'pendente')
                                <form action="{{ route('tarefa.iniciar', $tarefa) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                        Iniciar
                                    </button>
                                </form>
                            @endif

                            @if($tarefa->status == 'em_andamento')
                                @if($tarefa->pausada)
                                    <form action="{{ route('tarefa.continuar', $tarefa) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm">
                                            Continuar
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('tarefa.pausar', $tarefa) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm">
                                            Pausar
                                        </button>
                                    </form>
                                @endif
                                <button type="button" onclick="openConcluirModal({{ $tarefa->id }})" class="btn-primary-dark px-3 py-1 rounded text-sm">
                                    Concluir
                                </button>
                            @endif

                            <!-- Botão Ver -->
                            <a href="{{ route('tarefa.detalhes', $tarefa) }}" class="text-blue-400 hover:text-blue-900">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            @empty
            <li class="px-4 py-4 sm:px-6 text-center text-muted-dark">
                Nenhuma tarefa encontrada.
            </li>
            @endforelse
        </ul>
    </div>

    @if($tarefas->hasPages())
    <div class="mt-6">
        {{ $tarefas->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Modal Concluir Tarefa -->
<div id="concluirModal" class="fixed inset-0 bg-black bg-opacity-80 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md card-dark">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Concluir Tarefa</h3>
            <form id="concluirForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="observacoes_concluir" class="block text-sm font-medium text-muted-dark">Observações (opcional)</label>
                    <textarea name="observacoes" id="observacoes_concluir" rows="3" 
                              class="mt-1 block w-full input-dark rounded-md shadow-sm"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeConcluirModal()" class="btn-secondary-dark font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary-dark font-bold py-2 px-4 rounded">
                        Concluir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Exportar Tarefas -->
<div id="exportModal" class="fixed inset-0 bg-black bg-opacity-80 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md card-dark">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-primary-dark mb-4">Exportar Tarefas do Projeto</h3>
            <form id="exportForm" action="{{ route('tarefas.exportar') }}" method="GET" target="_blank">
                <div class="mb-4">
                    <label for="projeto_export" class="block text-sm font-medium text-muted-dark">Selecione o Projeto</label>
                    <select name="projeto_id" id="projeto_export" class="mt-1 block w-full input-dark rounded-md shadow-sm">
                        <option value="">Selecione um projeto</option>
                        @foreach($projetos as $projeto)
                            <option value="{{ $projeto->id }}">{{ $projeto->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeExportModal()" class="btn-secondary-dark font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Exportar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openConcluirModal(tarefaId) {
    document.getElementById('concluirForm').action = `/tarefa/${tarefaId}/concluir`;
    document.getElementById('concluirModal').classList.remove('hidden');
}

function closeConcluirModal() {
    document.getElementById('concluirModal').classList.add('hidden');
    document.getElementById('observacoes_concluir').value = '';
}

function openExportModal() {
    document.getElementById('exportModal').classList.remove('hidden');
}

function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
    document.getElementById('projeto_export').value = '';
}
</script>
@endsection