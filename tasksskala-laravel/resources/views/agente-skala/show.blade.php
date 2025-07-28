@extends('layouts.colaborador')

@section('title', 'Task #' . $task->id . ' - Agente Skala')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('agente-skala.index') }}" class="inline-flex items-center text-cyan-600 hover:text-cyan-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Voltar para lista
        </a>
    </div>

    <!-- Header -->
    <div class="bg-gradient-to-r from-cyan-600 to-teal-600 rounded-2xl p-8 text-white mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Task #{{ $task->id }}
                </h1>
                <p class="text-cyan-100">Detalhes da execução do Agente Skala</p>
            </div>
            <div class="text-right">
                @switch($task->status)
                    @case('aprovado')
                        <div class="bg-green-500/20 backdrop-blur-sm rounded-xl px-4 py-2 border border-green-400/30">
                            <p class="text-sm opacity-90">Status</p>
                            <p class="text-lg font-bold flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Aprovado
                            </p>
                        </div>
                        @break
                    @case('pendente')
                        <div class="bg-yellow-500/20 backdrop-blur-sm rounded-xl px-4 py-2 border border-yellow-400/30">
                            <p class="text-sm opacity-90">Status</p>
                            <p class="text-lg font-bold flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Pendente
                            </p>
                        </div>
                        @break
                    @case('rejeitado')
                        <div class="bg-red-500/20 backdrop-blur-sm rounded-xl px-4 py-2 border border-red-400/30">
                            <p class="text-sm opacity-90">Status</p>
                            <p class="text-lg font-bold flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Rejeitado
                            </p>
                        </div>
                        @break
                    @default
                        <div class="card-dark/20 backdrop-blur-sm rounded-xl px-4 py-2">
                            <p class="text-sm opacity-90">Status</p>
                            <p class="text-lg font-bold">{{ $task->status }}</p>
                        </div>
                @endswitch
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Task Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="card-dark rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-primary-dark mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informações da Task
                </h2>

                <div class="space-y-6">
                    <!-- Repository -->
                    <div>
                        <label class="block text-sm font-medium text-muted-dark mb-2">Repositório</label>
                        @if($task->repository_url)
                            <a href="{{ $task->repository_url }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-gray-50 rounded-lg text-blue-400 hover:text-blue-700 hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $task->repository_url }}
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        @else
                            <p class="text-muted-dark italic">Não informado</p>
                        @endif
                    </div>

                    <!-- Task Description -->
                    <div>
                        <label class="block text-sm font-medium text-muted-dark mb-2">Descrição da Task</label>
                        <div class="bg-gray-50 rounded-lg p-4 border">
                            <pre class="whitespace-pre-wrap text-sm text-primary-dark font-mono leading-relaxed">{{ $task->task_description }}</pre>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-muted-dark mb-2">Data de Criação</label>
                            <div class="flex items-center text-sm text-primary-dark">
                                <svg class="w-4 h-4 mr-2 text-muted-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $task->created_at ? $task->created_at->format('d/m/Y H:i:s') : 'Não informado' }}
                            </div>
                            @if($task->created_at)
                                <p class="text-xs text-muted-dark mt-1">{{ $task->created_at->diffForHumans() }}</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-muted-dark mb-2">Última Atualização</label>
                            <div class="flex items-center text-sm text-primary-dark">
                                <svg class="w-4 h-4 mr-2 text-muted-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                {{ $task->updated_at ? $task->updated_at->format('d/m/Y H:i:s') : 'Não informado' }}
                            </div>
                            @if($task->updated_at)
                                <p class="text-xs text-muted-dark mt-1">{{ $task->updated_at->diffForHumans() }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistics -->
            <div class="card-dark rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-primary-dark mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Estatísticas
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-400">Total de Planos:</span>
                        <span class="font-semibold text-primary-dark">{{ $task->plans->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <span class="text-sm text-gray-400">Planos Aprovados:</span>
                        <span class="font-semibold text-green-600">{{ $task->plans->where('approved', true)->count() }}</span>
                    </div>
                    @if($task->repository_id)
                        <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                            <span class="text-sm text-gray-400">Repository ID:</span>
                            <span class="font-semibold text-blue-400">{{ $task->repository_id }}</span>
                        </div>
                    @endif
                    @if($task->user_id)
                        <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                            <span class="text-sm text-gray-400">User ID:</span>
                            <span class="font-semibold text-purple-600">{{ $task->user_id }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Plans Section -->
    @if($task->plans->count() > 0)
    <div class="mt-8">
        <div class="card-dark rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-xl font-semibold text-primary-dark mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Planos Gerados ({{ $task->plans->count() }})
            </h3>

            <div class="space-y-6">
                @foreach($task->plans as $index => $plan)
                    <div class="border border-gray-700 rounded-xl p-6 {{ $plan->approved ? 'bg-green-50 border-green-200' : 'bg-gray-50' }} transition-all hover:shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-semibold text-primary-dark flex items-center">
                                <span class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center text-white text-sm font-bold mr-3">
                                    {{ $index + 1 }}
                                </span>
                                Plano #{{ $plan->id }}
                            </h4>
                            <div class="flex items-center space-x-3">
                                @if($plan->approved)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Aprovado
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-primary-dark">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        Pendente
                                    </span>
                                @endif
                                <span class="text-sm text-muted-dark">{{ $plan->created_at ? $plan->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-2 mb-4">
                            @if(!$plan->approved)
                                <form method="POST" action="{{ route('agente-skala.plan.status', $plan->id) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja aprovar este plano?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="approved" value="1">
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors transform hover:scale-105">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Aprovar
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('agente-skala.plan.status', $plan->id) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja reprovar este plano?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="approved" value="0">
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors transform hover:scale-105">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Reprovar
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Resultado do Plano (Markdown) -->
                        @if(isset($plan->plan_json['result']) && $plan->plan_json['result'])
                            <div class="card-dark rounded-lg border p-4 mb-4">
                                <label class="block text-sm font-medium text-muted-dark mb-3">Resultado do Plano:</label>
                                <div class="prose prose-sm max-w-none bg-gray-50 rounded-lg p-4 border">
                                    {!! \Illuminate\Support\Str::markdown($plan->plan_json['result']) !!}
                                </div>
                            </div>
                        @endif

                        <!-- JSON Completo (Colapsável) -->
                        <div class="card-dark rounded-lg border p-4">
                            <div class="flex justify-between items-center mb-3">
                                <label class="text-sm font-medium text-muted-dark">JSON Completo do Plano:</label>
                                <div class="flex items-center space-x-2">
                                    <button onclick="toggleJson('json-{{ $plan->id }}')" class="text-xs text-blue-400 hover:text-blue-700 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="icon-{{ $plan->id }}">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span id="text-{{ $plan->id }}">Mostrar JSON</span>
                                    </button>
                                    <button onclick="copyToClipboard('plan-{{ $plan->id }}')" class="text-xs text-blue-400 hover:text-blue-700 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        Copiar
                                    </button>
                                </div>
                            </div>
                            <div id="json-{{ $plan->id }}" class="hidden bg-gray-900 rounded-lg p-4 overflow-x-auto max-h-96 overflow-y-auto">
                                <pre id="plan-{{ $plan->id }}" class="text-green-400 text-sm font-mono whitespace-pre-wrap">{{ json_encode($plan->plan_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="mt-8">
        <div class="card-dark rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-primary-dark mb-2">Nenhum plano encontrado</h3>
            <p class="text-muted-dark">Esta task ainda não possui planos gerados pelo Agente Skala.</p>
        </div>
    </div>
    @endif
</div>

<script>
function toggleJson(elementId) {
    const element = document.getElementById(elementId);
    const icon = document.getElementById('icon-' + elementId.replace('json-', ''));
    const text = document.getElementById('text-' + elementId.replace('json-', ''));
    
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878a3 3 0 00-3-3m4.242 4.242L13 13l-1.414-1.414M13 13l.414.414M13 13a3 3 0 003-3M4 4l16 16"></path>';
        text.textContent = 'Ocultar JSON';
    } else {
        element.classList.add('hidden');
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
        text.textContent = 'Mostrar JSON';
    }
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent;
    
    navigator.clipboard.writeText(text).then(() => {
        // Show a simple feedback (you could use a toast library here)
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>Copiado!';
        
        setTimeout(() => {
            button.innerHTML = originalText;
        }, 2000);
    });
}
</script>
@endsection