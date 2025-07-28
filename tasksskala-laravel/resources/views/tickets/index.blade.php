@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary-dark">Tickets de Suporte</h1>
        <p class="mt-2 text-gray-400">Gerencie os tickets de suporte dos clientes</p>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="card-dark p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-primary-dark">{{ $stats['total'] }}</div>
            <div class="text-sm text-muted-dark">Total de Tickets</div>
        </div>
        <div class="bg-blue-900 bg-opacity-20 p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-blue-900">{{ $stats['abertos'] }}</div>
            <div class="text-sm text-blue-700">Abertos</div>
        </div>
        <div class="bg-yellow-900 bg-opacity-20 p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-yellow-900">{{ $stats['em_andamento'] }}</div>
            <div class="text-sm text-yellow-700">Em Andamento</div>
        </div>
        <div class="bg-purple-900 bg-opacity-20 p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-purple-900">{{ $stats['respondidos'] }}</div>
            <div class="text-sm text-purple-700">Respondidos</div>
        </div>
        <div class="bg-gray-800 p-6 rounded-lg shadow">
            <div class="text-2xl font-bold text-primary-dark">{{ $stats['fechados'] }}</div>
            <div class="text-sm text-muted-dark">Fechados</div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card-dark p-4 rounded-lg shadow mb-6">
        <form method="GET" action="{{ route('tickets.index') }}" class="flex flex-wrap gap-4">
            <select name="status" onchange="this.form.submit()" class="rounded-md border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todos os Status</option>
                <option value="aberto" {{ request('status') == 'aberto' ? 'selected' : '' }}>Aberto</option>
                <option value="em_andamento" {{ request('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                <option value="respondido" {{ request('status') == 'respondido' ? 'selected' : '' }}>Respondido</option>
                <option value="fechado" {{ request('status') == 'fechado' ? 'selected' : '' }}>Fechado</option>
            </select>
            
            <select name="setor" onchange="this.form.submit()" class="rounded-md border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todos os Setores</option>
                <option value="comercial" {{ request('setor') == 'comercial' ? 'selected' : '' }}>Comercial</option>
                <option value="financeiro" {{ request('setor') == 'financeiro' ? 'selected' : '' }}>Financeiro</option>
                <option value="desenvolvimento" {{ request('setor') == 'desenvolvimento' ? 'selected' : '' }}>Desenvolvimento</option>
            </select>
            
            <select name="prioridade" onchange="this.form.submit()" class="rounded-md border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todas as Prioridades</option>
                <option value="baixa" {{ request('prioridade') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                <option value="media" {{ request('prioridade') == 'media' ? 'selected' : '' }}>Média</option>
                <option value="alta" {{ request('prioridade') == 'alta' ? 'selected' : '' }}>Alta</option>
            </select>
            
            @if(request()->anyFilled(['status', 'setor', 'prioridade']))
                <a href="{{ route('tickets.index') }}" class="text-sm text-muted-dark hover:text-muted-dark">Limpar filtros</a>
            @endif
        </form>
    </div>

    <!-- Lista de Tickets -->
    <div class="card-dark shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-700">
            @forelse($tickets as $ticket)
            <li>
                <a href="{{ route('tickets.show', $ticket) }}" class="block hover:bg-gray-800 px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full {{ $ticket->prioridade == 'alta' ? 'bg-red-100' : ($ticket->prioridade == 'media' ? 'bg-yellow-100' : 'bg-green-100') }}">
                                    <span class="text-sm font-medium {{ $ticket->prioridade == 'alta' ? 'text-red-800' : ($ticket->prioridade == 'media' ? 'text-yellow-800' : 'text-green-800') }}">
                                        {{ strtoupper(substr($ticket->prioridade, 0, 1)) }}
                                    </span>
                                </span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-primary-dark">
                                    #{{ $ticket->id }} - {{ $ticket->titulo }}
                                </div>
                                <div class="text-sm text-muted-dark">
                                    <span>{{ $ticket->cliente->nome }}</span>
                                    @if($ticket->projeto)
                                        <span class="mx-2">•</span>
                                        <span>{{ $ticket->projeto->nome }}</span>
                                    @endif
                                    <span class="mx-2">•</span>
                                    <span>{{ $ticket->setor_label }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            @if($ticket->atribuidoPara)
                                <div class="text-sm text-muted-dark">
                                    <svg class="inline-block h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $ticket->atribuidoPara->nome }}
                                </div>
                            @endif
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->status_color }}">
                                    {{ $ticket->status_label }}
                                </span>
                            </div>
                            <div class="text-sm text-muted-dark">
                                {{ $ticket->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            @empty
            <li class="px-4 py-8 text-center text-muted-dark">
                Nenhum ticket encontrado.
            </li>
            @endforelse
        </ul>
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $tickets->withQueryString()->links() }}
    </div>
</div>
@endsection