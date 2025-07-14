@extends('layouts.admin')

@section('title', 'Detalhes do Colaborador')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $colaborador->nome }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Informações detalhadas do colaborador</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.colaboradores.edit', $colaborador) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Editar
                    </a>
                    <form action="{{ route('admin.colaboradores.destroy', $colaborador) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('Tem certeza que deseja excluir este colaborador?')">
                            Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Nome completo</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $colaborador->nome }}</dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $colaborador->email }}</dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Setor</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($colaborador->setor)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $colaborador->setor->nome }}
                            </span>
                            @if($colaborador->setor->descricao)
                                <p class="mt-1 text-sm text-gray-600">{{ $colaborador->setor->descricao }}</p>
                            @endif
                        @else
                            <span class="text-gray-400">Sem setor atribuído</span>
                        @endif
                    </dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Conhecimentos</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($colaborador->conhecimentos->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($colaborador->conhecimentos as $conhecimento)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $conhecimento->nome }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="mt-3 space-y-2">
                                @foreach($colaborador->conhecimentos as $conhecimento)
                                    @if($conhecimento->descricao)
                                        <div class="p-2 bg-gray-50 rounded">
                                            <strong class="text-xs text-gray-700">{{ $conhecimento->nome }}:</strong>
                                            <p class="text-xs text-gray-600 mt-1">{{ $conhecimento->descricao }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-400">Nenhum conhecimento atribuído</span>
                        @endif
                    </dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $colaborador->created_at->format('d/m/Y H:i') }}
                    </dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Última atualização</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $colaborador->updated_at->format('d/m/Y H:i') }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    
    <div class="mt-6">
        <a href="{{ route('admin.colaboradores.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            ← Voltar para lista
        </a>
    </div>
</div>
@endsection