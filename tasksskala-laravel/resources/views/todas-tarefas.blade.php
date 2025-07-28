@extends('layouts.colaborador')

@section('title', 'Todas as Tarefas')

@section('content')
<div class="space-y-6">
    <!-- Filtros -->
    <div class="card-dark shadow rounded-lg p-4">
        <form method="GET" action="{{ route('todas-tarefas') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-muted-dark">Colaborador</label>
                <select name="colaborador_id" class="mt-1 block w-full input-dark rounded-md shadow-sm">
                    <option value="">Todos</option>
                    @foreach($colaboradores as $colaborador)
                        <option value="{{ $colaborador->id }}" {{ request('colaborador_id') == $colaborador->id ? 'selected' : '' }}>
                            {{ $colaborador->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-muted-dark">Status</label>
                <select name="status" class="mt-1 block w-full input-dark rounded-md shadow-sm">
                    <option value="">Todos</option>
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

            <div>
                <label class="block text-sm font-medium text-muted-dark">Data Início</label>
                <input type="date" name="data_inicio" value="{{ request('data_inicio') }}" 
                       class="mt-1 block w-full input-dark rounded-md shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-muted-dark">Data Fim</label>
                <input type="date" name="data_fim" value="{{ request('data_fim') }}" 
                       class="mt-1 block w-full input-dark rounded-md shadow-sm">
            </div>

            <div class="md:col-span-4">
                <button type="submit" class="btn-primary-dark font-bold py-2 px-4 rounded">
                    Filtrar
                </button>
                <a href="{{ route('todas-tarefas') }}" class="ml-2 btn-secondary-dark font-bold py-2 px-4 rounded">
                    Limpar
                </a>
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
                                        <span class="font-semibold">Colaborador: {{ $tarefa->colaborador->nome }}</span>
                                        @if($tarefa->projeto)
                                            <span>Projeto: {{ $tarefa->projeto->nome }}</span>
                                        @endif
                                        @if($tarefa->data_vencimento)
                                            <span>Vencimento: {{ $tarefa->data_vencimento->format('d/m/Y H:i') }}</span>
                                        @endif
                                        @if($tarefa->criador && $tarefa->criador->id !== $tarefa->colaborador_id)
                                            <span>Criado por: {{ $tarefa->criador->nome }}</span>
                                        @endif
                                    </div>
                                    <div class="mt-2 flex items-center space-x-2">
                                        @php
                                            $statusColors = [
                                                'pendente' => 'bg-gray-800 text-gray-200',
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
                                                'manual' => 'bg-gray-800 text-gray-200',
                                                'automatica_feedback' => 'bg-purple-800 text-purple-200',
                                                'automatica_aprovacao' => 'bg-indigo-800 text-indigo-200'
                                            ];
                                        @endphp
                                        
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$tarefa->status] ?? 'bg-gray-800 text-gray-200' }}">
                                            {{ ucfirst(str_replace('_', ' ', $tarefa->status)) }}
                                        </span>
                                        
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioridadeColors[$tarefa->prioridade] ?? 'bg-gray-800 text-gray-200' }}">
                                            {{ ucfirst($tarefa->prioridade) }}
                                        </span>
                                        
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tipoColors[$tarefa->tipo] ?? 'bg-gray-800 text-gray-200' }}">
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
                            <!-- Botão Ver -->
                            <a href="{{ route('tarefa.detalhes', $tarefa) }}" class="text-blue-400 hover:text-blue-600">
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
@endsection