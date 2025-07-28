@extends('layouts.colaborador')

@section('title', 'Tarefas Designadas')

@section('content')
<div class="space-y-6">
    <!-- Filtros -->
    <div class="bg-white shadow rounded-lg p-4">
        <form method="GET" action="{{ route('tarefas-designadas') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="pendente_em_andamento" {{ (!request()->hasAny(['status', 'prioridade', 'projeto_id']) || request('status') == 'pendente_em_andamento') ? 'selected' : '' }}>Pendente + Em Andamento</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="em_andamento" {{ request('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                    <option value="concluida" {{ request('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                    <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Colaborador</label>
                <select name="colaborador_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    @foreach($colaboradores as $colab)
                        <option value="{{ $colab->id }}" {{ request('colaborador_id') == $colab->id ? 'selected' : '' }}>
                            {{ $colab->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Projeto</label>
                <select name="projeto_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    @foreach($projetos as $projeto)
                        <option value="{{ $projeto->id }}" {{ request('projeto_id') == $projeto->id ? 'selected' : '' }}>
                            {{ $projeto->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Filtrar
                </button>
                <a href="{{ route('tarefas-designadas') }}" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de Tarefas -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($tarefas as $tarefa)
            <li>
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-lg font-medium text-blue-600 truncate">
                                        {{ $tarefa->titulo }}
                                    </p>
                                    @if($tarefa->descricao)
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ Str::limit($tarefa->descricao, 100) }}
                                        </p>
                                    @endif
                                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                        <span class="font-semibold">Responsável: {{ $tarefa->colaborador->nome }}</span>
                                        @if($tarefa->projeto)
                                            <span>Projeto: {{ $tarefa->projeto->nome }}</span>
                                        @endif
                                        @if($tarefa->data_vencimento)
                                            <span>Vencimento: {{ $tarefa->data_vencimento->format('d/m/Y H:i') }}</span>
                                        @endif
                                        <span>Criada em: {{ $tarefa->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="mt-2 flex items-center space-x-2">
                                        @php
                                            $statusColors = [
                                                'pendente' => 'bg-gray-100 text-gray-800',
                                                'em_andamento' => 'bg-blue-100 text-blue-800',
                                                'concluida' => 'bg-green-100 text-green-800',
                                                'cancelada' => 'bg-red-100 text-red-800'
                                            ];
                                            $prioridadeColors = [
                                                'baixa' => 'bg-green-100 text-green-800',
                                                'media' => 'bg-yellow-100 text-yellow-800',
                                                'alta' => 'bg-orange-100 text-orange-800',
                                                'urgente' => 'bg-red-100 text-red-800'
                                            ];
                                            $tipoColors = [
                                                'manual' => 'bg-gray-100 text-gray-800',
                                                'automatica_feedback' => 'bg-purple-100 text-purple-800',
                                                'automatica_aprovacao' => 'bg-indigo-100 text-indigo-800'
                                            ];
                                        @endphp
                                        
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$tarefa->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $tarefa->status)) }}
                                        </span>
                                        
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioridadeColors[$tarefa->prioridade] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($tarefa->prioridade) }}
                                        </span>
                                        
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tipoColors[$tarefa->tipo] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ str_replace(['automatica_', '_'], ['Auto ', ' '], ucfirst($tarefa->tipo)) }}
                                        </span>

                                        @if($tarefa->recorrente)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Recorrente
                                            </span>
                                        @endif
                                        
                                        @if($tarefa->pausada)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pausada
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <!-- Botão Ver -->
                            <a href="{{ route('tarefa.detalhes', $tarefa) }}" class="text-blue-600 hover:text-blue-900 px-3 py-1 rounded text-sm border border-blue-600 hover:bg-blue-50">
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            @empty
            <li class="px-4 py-4 sm:px-6 text-center text-gray-500">
                Você ainda não designou nenhuma tarefa para outros colaboradores.
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