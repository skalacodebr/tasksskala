@extends('layouts.admin')

@section('title', 'Colaboradores')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Lista de Colaboradores</h2>
        <a href="{{ route('admin.colaboradores.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Novo Colaborador
        </a>
    </div>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-md">
    <ul class="divide-y divide-gray-200">
        @forelse($colaboradores as $colaborador)
        <li>
            <div class="px-4 py-4 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-700">
                                    {{ strtoupper(substr($colaborador->nome, 0, 2)) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="flex text-sm">
                                <p class="font-medium text-blue-600 truncate">
                                    {{ $colaborador->nome }}
                                </p>
                            </div>
                            <div class="flex">
                                <p class="text-sm text-gray-500">
                                    {{ $colaborador->email }} • {{ $colaborador->setor->nome ?? 'Sem setor' }}
                                </p>
                            </div>
                            @if($colaborador->conhecimentos->count() > 0)
                            <div class="mt-2">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($colaborador->conhecimentos as $conhecimento)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $conhecimento->nome }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.colaboradores.show', $colaborador) }}" class="text-blue-600 hover:text-blue-900">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.colaboradores.edit', $colaborador) }}" class="text-yellow-600 hover:text-yellow-900">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('admin.colaboradores.destroy', $colaborador) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir este colaborador?')">
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
            Nenhum colaborador encontrado.
            <a href="{{ route('admin.colaboradores.create') }}" class="text-blue-600 hover:text-blue-500 ml-1">Criar o primeiro colaborador</a>
        </li>
        @endforelse
    </ul>
</div>

@if($colaboradores->hasPages())
<div class="mt-6">
    {{ $colaboradores->links() }}
</div>
@endif
@endsection