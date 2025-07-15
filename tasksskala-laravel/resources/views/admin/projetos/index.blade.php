@extends('layouts.admin')

@section('title', 'Projetos')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Lista de Projetos</h2>
        <a href="{{ route('admin.projetos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Novo Projeto
        </a>
    </div>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-md">
    <ul class="divide-y divide-gray-200">
        @forelse($projetos as $projeto)
        <li>
            <div class="px-4 py-4 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-lg font-medium text-blue-600 truncate">
                                    {{ $projeto->nome }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ Str::limit($projeto->descricao, 100) }}
                                </p>
                                <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                    <span>Cliente: {{ $projeto->cliente->nome ?? 'N/A' }}</span>
                                    <span>Responsável: {{ $projeto->colaboradorResponsavel->nome ?? 'N/A' }}</span>
                                    <span>Prazo: {{ $projeto->prazo ? $projeto->prazo->format('d/m/Y') : 'Não definido' }}</span>
                                    <span>{{ $projeto->marcos_count }} marco(s)</span>
                                </div>
                                @if($projeto->repositorio_git)
                                <div class="mt-2">
                                    <a href="{{ $projeto->repositorio_git }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Ver Repositório
                                    </a>
                                </div>
                                @endif
                            </div>
                            <div class="ml-4 flex items-center space-x-2">
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
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.projetos.show', $projeto) }}" class="text-blue-600 hover:text-blue-900">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.projetos.edit', $projeto) }}" class="text-yellow-600 hover:text-yellow-900">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('admin.projetos.destroy', $projeto) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir este projeto?')">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </li>
        @empty
        <li class="px-4 py-4 sm:px-6 text-center text-gray-500">
            Nenhum projeto encontrado.
            <a href="{{ route('admin.projetos.create') }}" class="text-blue-600 hover:text-blue-500 ml-1">Criar o primeiro projeto</a>
        </li>
        @endforelse
    </ul>
</div>

@if($projetos->hasPages())
<div class="mt-6">
    {{ $projetos->links() }}
</div>
@endif
@endsection