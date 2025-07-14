@extends('layouts.admin')

@section('title', 'Detalhes do Status de Projeto')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-full flex items-center justify-center mr-4" style="background-color: {{ $statusProjeto->cor }}">
                        <div class="h-6 w-6 bg-white rounded-full"></div>
                    </div>
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $statusProjeto->nome }}</h3>
                        <p class="text-sm text-gray-500">Status de Projeto</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.status-projetos.edit', $statusProjeto) }}" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Editar
                    </a>
                    <a href="{{ route('admin.status-projetos.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Voltar
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informações Básicas -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-3">Informações Básicas</h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nome</dt>
                            <dd class="text-sm text-gray-900">{{ $statusProjeto->nome }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Cor</dt>
                            <dd class="text-sm text-gray-900 flex items-center">
                                <span class="inline-block w-4 h-4 rounded mr-2" style="background-color: {{ $statusProjeto->cor }}"></span>
                                {{ $statusProjeto->cor }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ordem</dt>
                            <dd class="text-sm text-gray-900">{{ $statusProjeto->ordem }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="text-sm">
                                @if($statusProjeto->ativo)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inativo
                                    </span>
                                @endif
                            </dd>
                        </div>
                        @if($statusProjeto->descricao)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                            <dd class="text-sm text-gray-900">{{ $statusProjeto->descricao }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Estatísticas -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-3">Estatísticas</h4>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total de Projetos</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $statusProjeto->projetos->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                            <dd class="text-sm text-gray-900">{{ $statusProjeto->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Última atualização</dt>
                            <dd class="text-sm text-gray-900">{{ $statusProjeto->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if($statusProjeto->projetos->count() > 0)
            <!-- Lista de Projetos -->
            <div class="mt-8">
                <h4 class="text-md font-medium text-gray-900 mb-4">Projetos com este Status</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <ul class="divide-y divide-gray-200">
                        @foreach($statusProjeto->projetos as $projeto)
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $projeto->nome }}</p>
                                @if($projeto->cliente)
                                <p class="text-xs text-gray-500">Cliente: {{ $projeto->cliente->nome }}</p>
                                @endif
                            </div>
                            <a href="{{ route('admin.projetos.show', $projeto) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                Ver projeto
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection