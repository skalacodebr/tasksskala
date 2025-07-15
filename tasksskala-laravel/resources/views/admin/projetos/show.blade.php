@extends('layouts.admin')

@section('title', 'Projeto: ' . $projeto->nome)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">{{ $projeto->nome }}</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.projetos.edit', $projeto) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Editar Projeto
            </a>
            <a href="{{ route('admin.projetos.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informações do Projeto -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informações do Projeto</h3>
                
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Cliente</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="{{ route('admin.clientes.show', $projeto->cliente) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $projeto->cliente->nome }}
                            </a>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Responsável</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="{{ route('admin.colaboradores.show', $projeto->colaboradorResponsavel) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $projeto->colaboradorResponsavel->nome }}
                            </a>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @php
                                $statusColors = [
                                    'em_andamento' => 'bg-blue-100 text-blue-800',
                                    'aprovacao_app' => 'bg-purple-100 text-purple-800',
                                    'concluido' => 'bg-green-100 text-green-800',
                                    'pausado' => 'bg-yellow-100 text-yellow-800',
                                    'cancelado' => 'bg-red-100 text-red-800'
                                ];
                                $statusLabels = [
                                    'em_andamento' => 'Em Andamento',
                                    'aprovacao_app' => 'Aprovação App',
                                    'concluido' => 'Concluído',
                                    'pausado' => 'Pausado',
                                    'cancelado' => 'Cancelado'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$projeto->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$projeto->status] ?? ucfirst($projeto->status) }}
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Prazo</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $projeto->prazo ? $projeto->prazo->format('d/m/Y') : 'Não definido' }}</dd>
                    </div>
                    
                    @if($projeto->repositorio_git)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Repositório</dt>
                        <dd class="mt-1 text-sm">
                            <a href="{{ $projeto->repositorio_git }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                Ver no Git
                            </a>
                        </dd>
                    </div>
                    @endif
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $projeto->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total de Marcos</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $projeto->marcos->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        @if($projeto->anotacoes)
        <!-- Anotações -->
        <div class="bg-white shadow rounded-lg mt-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Anotações</h3>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $projeto->anotacoes }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Descrição e Marcos -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Descrição -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Descrição do Projeto</h3>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $projeto->descricao }}</p>
            </div>
        </div>

        <!-- Marcos do Projeto -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Marcos do Projeto</h3>
                
                @if($projeto->marcos->count() > 0)
                    <div class="space-y-4">
                        @foreach($projeto->marcos->sortBy('prazo') as $marco)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $marco->nome }}</h4>
                                        
                                        @if($marco->descricao)
                                            <p class="text-sm text-gray-600 mt-1">{{ $marco->descricao }}</p>
                                        @endif
                                        
                                        <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                            <span>Prazo: {{ $marco->prazo->format('d/m/Y') }}</span>
                                            @if($marco->valor)
                                                <span>Valor: R$ {{ number_format($marco->valor, 2, ',', '.') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="ml-4">
                                        @php
                                            $marcoStatusColors = [
                                                'pendente' => 'bg-gray-100 text-gray-800',
                                                'entregue' => 'bg-blue-100 text-blue-800',
                                                'aprovado' => 'bg-green-100 text-green-800',
                                                'rejeitado' => 'bg-red-100 text-red-800'
                                            ];
                                            $marcoStatusLabels = [
                                                'pendente' => 'Pendente',
                                                'entregue' => 'Entregue',
                                                'aprovado' => 'Aprovado',
                                                'rejeitado' => 'Rejeitado'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $marcoStatusColors[$marco->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $marcoStatusLabels[$marco->status] ?? ucfirst($marco->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Resumo dos Marcos -->
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @php
                                $totalMarcos = $projeto->marcos->count();
                                $marcosPendentes = $projeto->marcos->where('status', 'pendente')->count();
                                $marcosEntregues = $projeto->marcos->where('status', 'entregue')->count();
                                $marcosAprovados = $projeto->marcos->where('status', 'aprovado')->count();
                                $marcosRejeitados = $projeto->marcos->where('status', 'rejeitado')->count();
                                $valorTotal = $projeto->marcos->sum('valor');
                                $valorAprovado = $projeto->marcos->where('status', 'aprovado')->sum('valor');
                            @endphp
                            
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $totalMarcos }}</div>
                                <div class="text-sm text-gray-500">Total</div>
                            </div>
                            
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $marcosEntregues }}</div>
                                <div class="text-sm text-gray-500">Entregues</div>
                            </div>
                            
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $marcosAprovados }}</div>
                                <div class="text-sm text-gray-500">Aprovados</div>
                            </div>
                            
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-600">{{ $marcosPendentes }}</div>
                                <div class="text-sm text-gray-500">Pendentes</div>
                            </div>
                        </div>
                        
                        @if($valorTotal > 0)
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-xl font-bold text-gray-900">R$ {{ number_format($valorTotal, 2, ',', '.') }}</div>
                                <div class="text-sm text-gray-500">Valor Total</div>
                            </div>
                            
                            <div class="text-center">
                                <div class="text-xl font-bold text-green-600">R$ {{ number_format($valorAprovado, 2, ',', '.') }}</div>
                                <div class="text-sm text-gray-500">Valor Aprovado</div>
                            </div>
                        </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum marco definido</h3>
                        <p class="mt-1 text-sm text-gray-500">Este projeto ainda não possui marcos cadastrados.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.projetos.edit', $projeto) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Adicionar marcos
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection