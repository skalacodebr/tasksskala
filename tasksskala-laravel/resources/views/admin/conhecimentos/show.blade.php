@extends('layouts.admin')

@section('title', 'Detalhes do Conhecimento')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $conhecimento->nome }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Informações detalhadas do conhecimento</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.conhecimentos.edit', $conhecimento) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Editar
                    </a>
                    @if($conhecimento->colaboradores->count() == 0)
                    <form action="{{ route('admin.conhecimentos.destroy', $conhecimento) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('Tem certeza que deseja excluir este conhecimento?')">
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
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $conhecimento->nome }}</dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $conhecimento->descricao ?: 'Sem descrição' }}
                    </dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Total de Colaboradores</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            {{ $conhecimento->colaboradores->count() }} colaboradores
                        </span>
                    </dd>
                </div>
                
                @if($conhecimento->colaboradores->count() > 0)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Colaboradores</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="space-y-2">
                            @foreach($conhecimento->colaboradores as $colaborador)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <div>
                                        <p class="font-medium">{{ $colaborador->nome }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $colaborador->email }} • {{ $colaborador->setor->nome ?? 'Sem setor' }}
                                        </p>
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
                        {{ $conhecimento->created_at->format('d/m/Y H:i') }}
                    </dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Última atualização</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $conhecimento->updated_at->format('d/m/Y H:i') }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    
    <div class="mt-6">
        <a href="{{ route('admin.conhecimentos.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            ← Voltar para lista
        </a>
    </div>
</div>
@endsection