@extends('layouts.admin')

@section('title', 'Agente Skala')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-robot text-blue-600 mr-3"></i>
            Tasks do Agente Skala
        </h2>
        <div class="flex items-center space-x-2">
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                Total: {{ $tasks->count() }}
            </span>
        </div>
    </div>

    <!-- Filtros e Estatísticas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Filtro por Repositório -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-filter text-purple-600 mr-2"></i>
                Filtros
            </h3>
            <form method="GET" action="{{ route('admin.agente-skala.index') }}" class="flex space-x-4">
                <div class="flex-1">
                    <label for="repository_filter" class="block text-sm font-medium text-gray-700 mb-2">Repositório</label>
                    <select name="repository_filter" id="repository_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todos os repositórios</option>
                        @foreach($repositories as $repo)
                            <option value="{{ $repo }}" {{ request('repository_filter') === $repo ? 'selected' : '' }}>
                                {{ basename($repo) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-1"></i>
                        Filtrar
                    </button>
                    @if(request('repository_filter'))
                        <a href="{{ route('admin.agente-skala.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            <i class="fas fa-times mr-1"></i>
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Card de Custo Total -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Custo Total</h3>
                    <p class="text-3xl font-bold">${{ number_format($totalCost, 2) }}</p>
                    <p class="text-green-100 text-sm mt-1">{{ $planCount }} plano(s) executado(s)</p>
                    @if(request('repository_filter'))
                        <p class="text-green-200 text-xs mt-2">
                            <i class="fas fa-filter mr-1"></i>
                            Filtrado por: {{ basename(request('repository_filter')) }}
                        </p>
                    @endif
                </div>
                <div class="text-green-200">
                    <i class="fas fa-dollar-sign text-4xl"></i>
                </div>
            </div>
        </div>
    </div>

    @if($tasks->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Repositório</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição da Task</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Planos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tasks as $task)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $task->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($task->repository_url)
                                    <a href="{{ $task->repository_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fab fa-github mr-1"></i>
                                        {{ Str::limit(basename($task->repository_url), 30) }}
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ Str::limit($task->task_description, 80) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($task->status)
                                    @case('aprovado')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Aprovado
                                        </span>
                                        @break
                                    @case('pendente')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Pendente
                                        </span>
                                        @break
                                    @case('rejeitado')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Rejeitado
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $task->status }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($task->plans->count() > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-file-alt mr-1"></i>
                                        {{ $task->plans->count() }} plano(s)
                                    </span>
                                @else
                                    <span class="text-gray-400">Nenhum plano</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $task->created_at ? $task->created_at->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.agente-skala.show', $task->id) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye mr-1"></i>
                                    Ver Detalhes
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-robot text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma task encontrada</h3>
            <p class="text-gray-500">Não há tasks do Agente Skala no momento.</p>
        </div>
    @endif
</div>

<style>
.table-container {
    max-height: 600px;
    overflow-y: auto;
}
</style>
@endsection