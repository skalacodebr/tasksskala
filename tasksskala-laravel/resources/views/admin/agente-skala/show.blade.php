@extends('layouts.admin')

@section('title', 'Detalhes da Task #' . $task->id)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.agente-skala.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i>
        Voltar para lista
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Task Details -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-tasks text-blue-600 mr-3"></i>
                    Task #{{ $task->id }}
                </h2>
                @switch($task->status)
                    @case('aprovado')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Aprovado
                        </span>
                        @break
                    @case('pendente')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            Pendente
                        </span>
                        @break
                    @case('rejeitado')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i>
                            Rejeitado
                        </span>
                        @break
                    @default
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            {{ $task->status }}
                        </span>
                @endswitch
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Repositório</label>
                    @if($task->repository_url)
                        <a href="{{ $task->repository_url }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fab fa-github mr-2"></i>
                            {{ $task->repository_url }}
                            <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                        </a>
                    @else
                        <span class="text-gray-400">Não informado</span>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descrição da Task</label>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="whitespace-pre-wrap text-sm text-gray-900 font-mono">{{ $task->task_description }}</pre>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Data de Criação</label>
                        <p class="text-sm text-gray-900">{{ $task->created_at ? $task->created_at->format('d/m/Y H:i:s') : 'Não informado' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Última Atualização</label>
                        <p class="text-sm text-gray-900">{{ $task->updated_at ? $task->updated_at->format('d/m/Y H:i:s') : 'Não informado' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Stats -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                Estatísticas
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total de Planos:</span>
                    <span class="font-semibold text-gray-900">{{ $task->plans->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Planos Aprovados:</span>
                    <span class="font-semibold text-green-600">{{ $task->plans->where('approved', true)->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Repository ID:</span>
                    <span class="font-semibold text-gray-900">{{ $task->repository_id ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">User ID:</span>
                    <span class="font-semibold text-gray-900">{{ $task->user_id ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Plans Section -->
@if($task->plans->count() > 0)
<div class="mt-8">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-file-alt text-purple-600 mr-3"></i>
            Planos ({{ $task->plans->count() }})
        </h3>

        <div class="space-y-6">
            @foreach($task->plans as $plan)
                <div class="border border-gray-200 rounded-lg p-4 {{ $plan->approved ? 'bg-green-50 border-green-200' : 'bg-gray-50' }}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">Plano #{{ $plan->id }}</h4>
                        <div class="flex items-center space-x-4">
                            @if($plan->approved)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Aprovado
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Pendente
                                </span>
                            @endif
                            <span class="text-sm text-gray-500">{{ $plan->created_at ? $plan->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-2 mb-4">
                        @if(!$plan->approved)
                            <form method="POST" action="{{ route('admin.agente-skala.plan.status', $plan->id) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja aprovar este plano?')">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="approved" value="1">
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors transform hover:scale-105">
                                    <i class="fas fa-check mr-1"></i>
                                    Aprovar
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.agente-skala.plan.status', $plan->id) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja reprovar este plano?')">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="approved" value="0">
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors transform hover:scale-105">
                                    <i class="fas fa-times mr-1"></i>
                                    Reprovar
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="bg-white rounded-lg p-4 border">
                        <label class="block text-sm font-medium text-gray-700 mb-2">JSON do Plano:</label>
                        <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                            <pre class="text-green-400 text-sm font-mono whitespace-pre-wrap">{{ json_encode($plan->plan_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection