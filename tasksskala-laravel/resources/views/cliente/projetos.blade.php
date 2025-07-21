@extends('layouts.cliente')

@section('title', 'Meus Projetos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Meus Projetos</h1>
                    <p class="text-gray-600 mt-1">Acompanhe o progresso de seus projetos</p>
                </div>
                <a href="{{ route('cliente.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Lista de Projetos -->
    @if($projetos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projetos as $projeto)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <!-- Header do Projeto -->
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $projeto->nome }}</h3>
                            @if($projeto->statusProjeto)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                      style="background-color: {{ $projeto->statusProjeto->cor }}20; color: {{ $projeto->statusProjeto->cor }};">
                                    {{ $projeto->statusProjeto->nome }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                           @if($projeto->status == 'em_andamento') bg-blue-100 text-blue-800
                                           @elseif($projeto->status == 'concluido') bg-green-100 text-green-800
                                           @elseif($projeto->status == 'pausado') bg-yellow-100 text-yellow-800
                                           @elseif($projeto->status == 'cancelado') bg-red-100 text-red-800
                                           @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $projeto->status)) }}
                                </span>
                            @endif
                        </div>

                        <!-- Descrição -->
                        @if($projeto->descricao)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($projeto->descricao, 150) }}</p>
                        @endif

                        <!-- Informações -->
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            @if($projeto->colaboradorResponsavel)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Responsável: {{ $projeto->colaboradorResponsavel->nome }}</span>
                                </div>
                            @endif
                            
                            @if($projeto->prazo)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Prazo: {{ $projeto->prazo->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Ações -->
                        <div class="flex justify-end">
                            <a href="{{ route('cliente.projeto.detalhes', $projeto) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Estado Vazio -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-8 sm:p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum projeto encontrado</h3>
                <p class="text-gray-600">Você ainda não possui projetos atribuídos.</p>
            </div>
        </div>
    @endif
</div>
@endsection