@extends('layouts.colaborador')

@section('title', 'Projetos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Projetos</h1>
                    <p class="text-gray-600 mt-1">Gerencie todos os projetos da empresa</p>
                </div>
                <a href="{{ route('projetos.criar') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Novo Projeto
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('projetos.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Filtro por nome -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar por nome</label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}"
                               placeholder="Digite o nome do projeto..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Filtro por responsável -->
                    <div>
                        <label for="responsavel_id" class="block text-sm font-medium text-gray-700 mb-1">Responsável</label>
                        <select name="responsavel_id" 
                                id="responsavel_id" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Todos os responsáveis</option>
                            @foreach(\App\Models\Colaborador::orderBy('nome')->get() as $colaborador)
                                <option value="{{ $colaborador->id }}" {{ request('responsavel_id') == $colaborador->id ? 'selected' : '' }}>
                                    {{ $colaborador->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botões de ação -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filtrar
                        </button>
                        @if(request()->hasAny(['search', 'responsavel_id']))
                            <a href="{{ route('projetos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                                Limpar
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Projetos -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            @if($projetos->count() > 0)
                <div class="space-y-4">
                    @foreach($projetos as $projeto)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            <a href="{{ route('projetos.show', $projeto) }}" class="hover:text-blue-600">
                                                {{ $projeto->nome }}
                                            </a>
                                        </h3>
                                        
                                        <!-- Status Badge -->
                                        @php
                                            $statusColors = [
                                                'planejamento' => 'bg-gray-100 text-gray-800',
                                                'em_andamento' => 'bg-blue-100 text-blue-800',
                                                'em_teste' => 'bg-yellow-100 text-yellow-800',
                                                'aprovacao_app' => 'bg-purple-100 text-purple-800',
                                                'concluido' => 'bg-green-100 text-green-800',
                                                'cancelado' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$projeto->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $projeto->status)) }}
                                        </span>
                                    </div>

                                    <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                        <div>
                                            <span class="font-medium">Cliente:</span> {{ $projeto->cliente->nome ?? 'Não informado' }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Responsável:</span> {{ $projeto->colaboradorResponsavel->nome ?? 'Não informado' }}
                                        </div>
                                        @if($projeto->prazo)
                                            <div>
                                                <span class="font-medium">Prazo:</span> 
                                                <span class="{{ $projeto->prazo->isPast() ? 'text-red-600' : '' }}">
                                                    {{ $projeto->prazo->format('d/m/Y') }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    @if($projeto->descricao)
                                        <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $projeto->descricao }}</p>
                                    @endif

                                    @if($projeto->link_repositorio)
                                        <div class="mt-2">
                                            <a href="{{ $projeto->link_repositorio }}" target="_blank" 
                                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path>
                                                    <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-1a1 1 0 10-2 0v1H5V7h1a1 1 0 000-2H5z"></path>
                                                </svg>
                                                Repositório
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('projetos.show', $projeto) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        Ver
                                    </a>
                                    <a href="{{ route('projetos.edit', $projeto) }}" 
                                       class="text-green-600 hover:text-green-800 font-medium text-sm">
                                        Editar
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginação -->
                <div class="mt-6">
                    {{ $projetos->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum projeto encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500">Comece criando um novo projeto.</p>
                    <div class="mt-6">
                        <a href="{{ route('projetos.criar') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Novo Projeto
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection