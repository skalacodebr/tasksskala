@extends('layouts.admin')

@section('title', 'Detalhes do Setor')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $setor->nome }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Informações detalhadas do setor</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.setores.edit', $setor) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Editar
                    </a>
                    @if($setor->colaboradores->count() == 0)
                    <form action="{{ route('admin.setores.destroy', $setor) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('Tem certeza que deseja excluir este setor?')">
                            Excluir
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Nome</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $setor->nome }}</dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $setor->descricao ?: 'Sem descrição' }}
                    </dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Total de Colaboradores</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $setor->colaboradores->count() }} colaboradores
                        </span>
                    </dd>
                </div>
                
                @if($setor->colaboradores->count() > 0)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Colaboradores</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="space-y-2">
                            @foreach($setor->colaboradores as $colaborador)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <div>
                                        <p class="font-medium">{{ $colaborador->nome }}</p>
                                        <p class="text-xs text-gray-500">{{ $colaborador->email }}</p>
                                    </div>
                                    <a href="{{ route('admin.colaboradores.show', $colaborador) }}" 
                                       class="text-blue-600 hover:text-blue-900 text-sm">
                                        Ver detalhes
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </dd>
                </div>
                @endif
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $setor->created_at->format('d/m/Y H:i') }}
                    </dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Última atualização</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $setor->updated_at->format('d/m/Y H:i') }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    
    <div class="mt-6">
        <a href="{{ route('admin.setores.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            ← Voltar para lista
        </a>
    </div>
</div>
@endsection