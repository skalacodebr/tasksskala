@extends('layouts.cliente')

@section('title', 'Detalhes do Projeto')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $projeto->nome }}</h1>
                    <div class="flex items-center space-x-4 mt-2">
                        @if($projeto->statusProjeto)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" 
                                  style="background-color: {{ $projeto->statusProjeto->cor }}20; color: {{ $projeto->statusProjeto->cor }};">
                                {{ $projeto->statusProjeto->nome }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                       @if($projeto->status == 'em_andamento') bg-blue-100 text-blue-800
                                       @elseif($projeto->status == 'concluido') bg-green-100 text-green-800
                                       @elseif($projeto->status == 'pausado') bg-yellow-100 text-yellow-800
                                       @elseif($projeto->status == 'cancelado') bg-red-100 text-red-800
                                       @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $projeto->status)) }}
                            </span>
                        @endif
                        
                        @if($projeto->prazo)
                            <span class="text-sm text-gray-600">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Prazo: {{ $projeto->prazo->format('d/m/Y') }}
                            </span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('cliente.projetos') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Informações Gerais -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informações do Projeto</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Responsável</label>
                    <p class="text-gray-900">{{ $projeto->colaboradorResponsavel->nome ?? 'Não atribuído' }}</p>
                </div>
                
                @if($projeto->repositorio_git)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Repositório</label>
                        <a href="{{ $projeto->repositorio_git }}" target="_blank" 
                           class="text-blue-600 hover:text-blue-800 underline break-all">
                            {{ $projeto->repositorio_git }}
                        </a>
                    </div>
                @endif
            </div>
            
            @if($projeto->descricao)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $projeto->descricao }}</p>
                </div>
            @endif
            
            @if($projeto->anotacoes)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Anotações</label>
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $projeto->anotacoes }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Instruções de Acesso -->
    @if($projeto->instrucoes_ambiente_teste || $projeto->instrucoes_ambiente_producao)
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Instruções de Acesso</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($projeto->instrucoes_ambiente_teste)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="text-md font-medium text-blue-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Ambiente de Teste
                            </h3>
                            <div class="text-blue-800 whitespace-pre-wrap text-sm">{{ $projeto->instrucoes_ambiente_teste }}</div>
                        </div>
                    @endif
                    
                    @if($projeto->instrucoes_ambiente_producao)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h3 class="text-md font-medium text-green-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                Ambiente de Produção
                            </h3>
                            <div class="text-green-800 whitespace-pre-wrap text-sm">{{ $projeto->instrucoes_ambiente_producao }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Marcos do Projeto -->
    @if($projeto->marcos->count() > 0)
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Marcos do Projeto</h2>
                
                <div class="space-y-4">
                    @foreach($projeto->marcos as $marco)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-medium text-gray-900">{{ $marco->nome }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                           @if($marco->status == 'pendente') bg-yellow-100 text-yellow-800
                                           @elseif($marco->status == 'entregue') bg-blue-100 text-blue-800
                                           @elseif($marco->status == 'aprovado') bg-green-100 text-green-800
                                           @elseif($marco->status == 'rejeitado') bg-red-100 text-red-800
                                           @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($marco->status) }}
                                </span>
                            </div>
                            
                            @if($marco->descricao)
                                <p class="text-gray-600 text-sm mb-2">{{ $marco->descricao }}</p>
                            @endif
                            
                            <div class="flex justify-between items-center text-sm text-gray-500">
                                @if($marco->prazo)
                                    <span>Prazo: {{ $marco->prazo->format('d/m/Y') }}</span>
                                @endif
                                @if($marco->valor)
                                    <span>Valor: R$ {{ number_format($marco->valor, 2, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection