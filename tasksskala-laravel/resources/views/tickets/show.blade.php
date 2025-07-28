@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-primary-dark">Ticket #{{ $ticket->id }}</h1>
                    <p class="mt-1 text-gray-400">{{ $ticket->titulo }}</p>
                </div>
                <a href="{{ route('tickets.index') }}" class="text-indigo-600 hover:text-indigo-900">
                    ← Voltar para lista
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Conteúdo Principal -->
            <div class="lg:col-span-2">
                <!-- Descrição -->
                <div class="card-dark shadow rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-medium text-primary-dark mb-4">Descrição</h2>
                    <div class="prose max-w-none text-muted-dark">
                        {{ $ticket->descricao }}
                    </div>
                </div>

                <!-- Mensagens -->
                <div class="card-dark shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-primary-dark mb-4">Histórico de Mensagens</h2>
                    
                    <div class="space-y-4">
                        @foreach($ticket->mensagens as $mensagem)
                            @if(!$mensagem->is_internal || $mensagem->is_internal)
                                <div class="border-l-4 {{ $mensagem->cliente_id ? 'border-blue-400' : 'border-green-400' }} pl-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-primary-dark">
                                                @if($mensagem->cliente_id)
                                                    {{ $mensagem->cliente->nome }} (Cliente)
                                                @else
                                                    {{ $mensagem->colaborador->nome }}
                                                @endif
                                            </p>
                                            <p class="text-sm text-muted-dark">
                                                {{ $mensagem->created_at->format('d/m/Y H:i') }}
                                                @if($mensagem->is_internal)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Nota Interna
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-muted-dark">
                                        {{ $mensagem->mensagem }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Formulário de Resposta -->
                    <form action="{{ route('tickets.responder', $ticket) }}" method="POST" class="mt-6 border-t pt-6">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="mensagem" class="block text-sm font-medium text-muted-dark">
                                    Nova Mensagem
                                </label>
                                <textarea name="mensagem" id="mensagem" rows="4" required
                                    class="mt-1 block w-full rounded-md border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="is_internal" id="is_internal" value="1"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-600 rounded">
                                <label for="is_internal" class="ml-2 block text-sm text-primary-dark">
                                    Nota interna (não visível para o cliente)
                                </label>
                            </div>
                            
                            <div>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Enviar Resposta
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Informações do Ticket -->
                <div class="card-dark shadow rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-primary-dark mb-4">Informações</h3>
                    
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->status_color }}">
                                    {{ $ticket->status_label }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Prioridade</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->prioridade_color }}">
                                    {{ $ticket->prioridade_label }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Setor</dt>
                            <dd class="mt-1 text-sm text-primary-dark">{{ $ticket->setor_label }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Cliente</dt>
                            <dd class="mt-1 text-sm text-primary-dark">{{ $ticket->cliente->nome }}</dd>
                        </div>
                        
                        @if($ticket->projeto)
                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Projeto</dt>
                            <dd class="mt-1 text-sm text-primary-dark">{{ $ticket->projeto->nome }}</dd>
                        </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Criado em</dt>
                            <dd class="mt-1 text-sm text-primary-dark">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        
                        @if($ticket->respondido_em)
                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Respondido em</dt>
                            <dd class="mt-1 text-sm text-primary-dark">{{ $ticket->respondido_em->format('d/m/Y H:i') }}</dd>
                        </div>
                        @endif
                        
                        @if($ticket->fechado_em)
                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Fechado em</dt>
                            <dd class="mt-1 text-sm text-primary-dark">{{ $ticket->fechado_em->format('d/m/Y H:i') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Atribuição -->
                <div class="card-dark shadow rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-primary-dark mb-4">Atribuição</h3>
                    
                    @if($ticket->atribuidoPara)
                        <p class="text-sm text-muted-dark mb-4">
                            Atribuído para: <strong>{{ $ticket->atribuidoPara->nome }}</strong>
                        </p>
                        
                        <!-- Formulário de Transferência -->
                        <form action="{{ route('tickets.transferir', $ticket) }}" method="POST" class="space-y-3 mb-4">
                            @csrf
                            <div>
                                <label for="novo_responsavel_id" class="block text-sm font-medium text-muted-dark">
                                    Transferir responsabilidade para
                                </label>
                                <select name="novo_responsavel_id" id="novo_responsavel_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Selecione um colaborador</option>
                                    @foreach($colaboradores as $colaborador)
                                        @if($colaborador->id != $ticket->atribuido_para)
                                            <option value="{{ $colaborador->id }}">
                                                {{ $colaborador->nome }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                Transferir Responsabilidade
                            </button>
                        </form>
                    @else
                        <p class="text-sm text-muted-dark mb-4">Não atribuído</p>
                    @endif
                    
                    <form action="{{ route('tickets.atribuir', $ticket) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label for="colaborador_id" class="block text-sm font-medium text-muted-dark">
                                Atribuir para
                            </label>
                            <select name="colaborador_id" id="colaborador_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Selecione um colaborador</option>
                                @foreach($colaboradores as $colaborador)
                                    <option value="{{ $colaborador->id }}" {{ $ticket->atribuido_para == $colaborador->id ? 'selected' : '' }}>
                                        {{ $colaborador->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Atribuir
                        </button>
                    </form>
                </div>

                <!-- Ações -->
                <div class="card-dark shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-primary-dark mb-4">Ações</h3>
                    
                    <form action="{{ route('tickets.status', $ticket) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label for="status" class="block text-sm font-medium text-muted-dark">
                                Alterar Status
                            </label>
                            <select name="status" id="status" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="aberto" {{ $ticket->status == 'aberto' ? 'selected' : '' }}>Aberto</option>
                                <option value="em_andamento" {{ $ticket->status == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                                <option value="respondido" {{ $ticket->status == 'respondido' ? 'selected' : '' }}>Respondido</option>
                                <option value="fechado" {{ $ticket->status == 'fechado' ? 'selected' : '' }}>Fechado</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Alterar Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection