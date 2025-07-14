@extends('layouts.admin')

@section('title', 'Cliente: ' . $cliente->nome)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">{{ $cliente->nome }}</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.clientes.edit', $cliente) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Editar Cliente
            </a>
            <a href="{{ route('admin.clientes.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informações do Cliente -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informações do Cliente</h3>
                
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nome</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $cliente->nome }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $cliente->email }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Cadastrado em</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $cliente->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total de Projetos</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $cliente->projetos->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Lista de Projetos -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Projetos</h3>
                    <a href="{{ route('admin.projetos.create', ['cliente_id' => $cliente->id]) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                        Novo Projeto
                    </a>
                </div>
                
                @if($cliente->projetos->count() > 0)
                    <div class="space-y-4">
                        @foreach($cliente->projetos as $projeto)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-medium text-gray-900">
                                            <a href="{{ route('admin.projetos.show', $projeto) }}" class="hover:text-blue-600">
                                                {{ $projeto->nome }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($projeto->descricao, 100) }}</p>
                                        
                                        <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                            <span>Prazo: {{ $projeto->prazo->format('d/m/Y') }}</span>
                                            <span>Responsável: {{ $projeto->colaboradorResponsavel->nome ?? 'Não definido' }}</span>
                                        </div>
                                        
                                        @if($projeto->repositorio_git)
                                            <div class="mt-2">
                                                <a href="{{ $projeto->repositorio_git }}" target="_blank" 
                                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                                    Ver Repositório
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="ml-4">
                                        @php
                                            $statusColors = [
                                                'em_andamento' => 'bg-blue-100 text-blue-800',
                                                'concluido' => 'bg-green-100 text-green-800',
                                                'pausado' => 'bg-yellow-100 text-yellow-800',
                                                'cancelado' => 'bg-red-100 text-red-800'
                                            ];
                                            $statusLabels = [
                                                'em_andamento' => 'Em Andamento',
                                                'concluido' => 'Concluído',
                                                'pausado' => 'Pausado',
                                                'cancelado' => 'Cancelado'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$projeto->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabels[$projeto->status] ?? ucfirst($projeto->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum projeto</h3>
                        <p class="mt-1 text-sm text-gray-500">Este cliente ainda não possui projetos cadastrados.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.projetos.create', ['cliente_id' => $cliente->id]) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Criar primeiro projeto
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection