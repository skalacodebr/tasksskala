@extends('layouts.colaborador')

@section('title', 'Detalhes do Projeto')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="card-dark shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-primary-dark">{{ $projeto->nome }}</h1>
                    
                    <!-- Status e Info Básica -->
                    <div class="flex flex-wrap gap-3 mt-3">
                        @php
                            $statusColors = [
                                'planejamento' => 'bg-gray-100 text-primary-dark',
                                'em_andamento' => 'bg-blue-100 text-blue-800',
                                'em_teste' => 'bg-yellow-100 text-yellow-800',
                                'aprovacao_app' => 'bg-purple-100 text-purple-800',
                                'concluido' => 'bg-green-100 text-green-800',
                                'cancelado' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$projeto->status] ?? 'bg-gray-100 text-primary-dark' }}">
                            {{ ucfirst(str_replace('_', ' ', $projeto->status)) }}
                        </span>
                        
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Cliente: {{ $projeto->cliente->nome ?? 'Não informado' }}
                        </span>
                        
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Responsável: {{ $projeto->colaboradorResponsavel->nome ?? 'Não informado' }}
                        </span>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <a href="{{ route('projetos.edit', $projeto) }}" 
                       class="btn-primary-dark font-bold py-2 px-4 rounded">
                        Editar
                    </a>
                    <a href="{{ route('projetos.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-primary-dark font-bold py-2 px-4 rounded">
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Principais -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Descrição -->
            <div class="card-dark shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">Descrição</h3>
                    
                    @if($projeto->descricao)
                        <div class="prose max-w-none">
                            <p class="text-muted-dark whitespace-pre-line">{{ $projeto->descricao }}</p>
                        </div>
                    @else
                        <p class="text-muted-dark italic">Nenhuma descrição fornecida.</p>
                    @endif
                </div>
            </div>

            <!-- Marcos do Projeto -->
            @if($projeto->marcos->count() > 0)
                <div class="card-dark shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">Marcos do Projeto</h3>
                        
                        <div class="space-y-3">
                            @foreach($projeto->marcos as $marco)
                                <div class="flex items-center justify-between p-3 border border-gray-700 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-primary-dark">{{ $marco->nome }}</h4>
                                        @if($marco->prazo)
                                            <p class="text-sm text-gray-400">
                                                Prazo: {{ $marco->prazo->format('d/m/Y') }}
                                                @if($marco->prazo->isPast())
                                                    <span class="text-red-400 font-medium">(Atrasado)</span>
                                                @elseif($marco->prazo->isToday())
                                                    <span class="text-yellow-600 font-medium">(Hoje)</span>
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                    <!-- Colaboradores não veem o valor -->
                                    <div class="text-right">
                                        <span class="text-sm text-muted-dark">Marco definido</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tarefas do Projeto -->
            @if($projeto->tarefas->count() > 0)
                <div class="card-dark shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">Tarefas Relacionadas</h3>
                        
                        <div class="space-y-3">
                            @foreach($projeto->tarefas->take(10) as $tarefa)
                                <div class="flex items-center justify-between p-3 border border-gray-700 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-primary-dark">
                                            <a href="{{ route('tarefa.detalhes', $tarefa) }}" class="hover:text-blue-400">
                                                {{ $tarefa->titulo }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-400">
                                            Responsável: {{ $tarefa->colaborador->nome }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $statusColors = [
                                                'pendente' => 'bg-gray-100 text-primary-dark',
                                                'em_andamento' => 'bg-blue-100 text-blue-800',
                                                'concluida' => 'bg-green-100 text-green-800',
                                                'cancelada' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$tarefa->status] ?? 'bg-gray-100 text-primary-dark' }}">
                                            {{ ucfirst(str_replace('_', ' ', $tarefa->status)) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($projeto->tarefas->count() > 10)
                                <div class="text-center pt-3 border-t">
                                    <p class="text-sm text-muted-dark">
                                        ... e mais {{ $projeto->tarefas->count() - 10 }} tarefas
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Anotações -->
            @if($projeto->anotacoes)
                <div class="card-dark shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">Anotações</h3>
                        <div class="prose max-w-none">
                            <p class="text-muted-dark whitespace-pre-line">{{ $projeto->anotacoes }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Informações Gerais -->
            <div class="card-dark shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">Informações</h3>
                    
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Status</dt>
                            <dd class="mt-1 text-sm text-primary-dark">{{ ucfirst(str_replace('_', ' ', $projeto->status)) }}</dd>
                        </div>

                        @if($projeto->prazo)
                            <div>
                                <dt class="text-sm font-medium text-muted-dark">Prazo de Entrega</dt>
                                <dd class="mt-1 text-sm text-primary-dark">
                                    {{ $projeto->prazo->format('d/m/Y') }}
                                    @if($projeto->prazo->isPast() && $projeto->status !== 'concluido')
                                        <span class="text-red-400 text-xs">(Atrasado)</span>
                                    @endif
                                </dd>
                                <dd class="text-xs text-muted-dark">{{ $projeto->prazo->diffForHumans() }}</dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Criado em</dt>
                            <dd class="mt-1 text-sm text-primary-dark">{{ $projeto->created_at->format('d/m/Y H:i') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Atualizado em</dt>
                            <dd class="mt-1 text-sm text-primary-dark">{{ $projeto->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Informações do Cliente -->
            <div class="card-dark shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">Cliente</h3>
                    
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Nome</dt>
                            <dd class="mt-1 text-sm text-primary-dark">{{ $projeto->cliente->nome ?? 'Não informado' }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Email</dt>
                            <dd class="mt-1 text-sm text-primary-dark">{{ $projeto->cliente->email ?? 'Não informado' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Links e Ações -->
            <div class="card-dark shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">Links</h3>
                    
                    <div class="space-y-3">
                        @if($projeto->link_repositorio)
                            <a href="{{ $projeto->link_repositorio }}" target="_blank" 
                               class="flex items-center text-blue-400 hover:text-blue-300">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path>
                                    <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-1a1 1 0 10-2 0v1H5V7h1a1 1 0 000-2H5z"></path>
                                </svg>
                                Repositório Git
                            </a>
                        @endif
                        
                        <a href="{{ route('tarefa.criar') }}?projeto_id={{ $projeto->id }}" 
                           class="flex items-center text-green-600 hover:text-green-800">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Criar Tarefa para este Projeto
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection