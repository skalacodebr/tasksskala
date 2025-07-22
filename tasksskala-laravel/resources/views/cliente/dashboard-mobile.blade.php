@extends('layouts.cliente-mobile')

@section('header-title', 'Dashboard')

@section('content')
<!-- Welcome Card -->
<div class="p-4">
    <div class="bg-gradient-to-r from-blue-700 to-blue-900 rounded-2xl p-6 text-white">
        <h1 class="text-xl font-bold mb-1">Olá, {{ $cliente->nome }}!</h1>
        <p class="text-blue-100 text-sm">Gerencie suas solicitações</p>
        
        <div class="mt-4 flex items-center justify-between">
            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 py-2">
                <p class="text-xs opacity-90">Total de Tasks</p>
                <p class="text-xl font-bold">{{ $tasks->count() }}</p>
            </div>
            <a href="{{ route('cliente.criar-task') }}" class="bg-white text-blue-700 px-4 py-2 rounded-lg font-medium text-sm touch-feedback">
                Nova Task
            </a>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="px-4 grid grid-cols-2 gap-3 mb-4">
    <div class="bg-white rounded-xl p-4 border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <span class="text-2xl font-bold">{{ $tasks->where('status', 'aprovado')->count() }}</span>
        </div>
        <p class="text-sm text-gray-600">Aprovadas</p>
    </div>
    
    <div class="bg-white rounded-xl p-4 border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-2xl font-bold">{{ $tasks->where('status', 'pendente')->count() }}</span>
        </div>
        <p class="text-sm text-gray-600">Pendentes</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="px-4 mb-6">
    <h2 class="text-lg font-semibold mb-3">Ações Rápidas</h2>
    
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('cliente.criar-task') }}" class="touch-feedback bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <p class="font-medium">Nova Task</p>
            <p class="text-xs opacity-90">Criar solicitação</p>
        </a>
        
        <a href="{{ route('cliente.projetos') }}" class="touch-feedback bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <p class="font-medium">Projetos</p>
            <p class="text-xs opacity-90">{{ $projetos->count() }} ativos</p>
        </a>
    </div>
</div>

<!-- Recent Tasks -->
@if($tasks->count() > 0)
<div class="px-4">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-lg font-semibold">Tasks Recentes</h2>
        <a href="{{ route('cliente.minhas-tasks') }}" class="text-sm text-blue-600">Ver todas</a>
    </div>
    
    <div class="space-y-3">
        @foreach($tasks->take(5) as $task)
        <a href="{{ route('cliente.task.detalhes', $task->id) }}" class="block bg-white rounded-lg p-4 border border-gray-100 touch-feedback">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-1">
                        <span class="text-xs font-bold text-gray-500">#{{ $task->id }}</span>
                        @switch($task->status)
                            @case('aprovado')
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aprovado
                                </span>
                                @break
                            @case('pendente')
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pendente
                                </span>
                                @break
                            @default
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $task->status }}
                                </span>
                        @endswitch
                    </div>
                    <p class="text-sm font-medium text-gray-900 mb-1">{{ Str::limit($task->task_description, 60) }}</p>
                    <p class="text-xs text-gray-500">{{ $task->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>
        @endforeach
    </div>
</div>
@else
<div class="px-4">
    <div class="bg-gray-50 rounded-xl p-8 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
        </svg>
        <h3 class="text-base font-medium text-gray-900 mb-1">Nenhuma task ainda</h3>
        <p class="text-sm text-gray-500 mb-4">Crie sua primeira solicitação</p>
        <a href="{{ route('cliente.criar-task') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg text-sm touch-feedback">
            Criar Primeira Task
        </a>
    </div>
</div>
@endif
@endsection