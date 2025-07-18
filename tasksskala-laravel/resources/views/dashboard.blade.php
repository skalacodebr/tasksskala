@extends('layouts.colaborador')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Cartões de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium">P</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pendentes</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $tarefasPendentes }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium">A</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Em Andamento</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $tarefasEmAndamento }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium">C</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Concluídas Hoje</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $tarefasConcluidas }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium">!</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Atrasadas</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $tarefasAtrasadas }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid principal -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Próximas Tarefas -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Próximas Tarefas</h3>
                
                @if($proximasTarefas->count() > 0)
                    <div class="space-y-3">
                        @foreach($proximasTarefas as $tarefa)
                            <div class="border-l-4 border-blue-400 pl-4 py-2">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $tarefa->titulo }}</h4>
                                        @if($tarefa->projeto)
                                            <p class="text-xs text-gray-500">Projeto: {{ $tarefa->projeto->nome }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500">
                                            Vence em: {{ $tarefa->data_vencimento->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="ml-4">
                                        @php
                                            $prioridadeColors = [
                                                'baixa' => 'bg-green-100 text-green-800',
                                                'media' => 'bg-yellow-100 text-yellow-800',
                                                'alta' => 'bg-orange-100 text-orange-800',
                                                'urgente' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $prioridadeColors[$tarefa->prioridade] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($tarefa->prioridade) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('minhas-tarefas') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                            Ver todas as tarefas →
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Nenhuma tarefa com vencimento próximo.</p>
                @endif
            </div>
        </div>

        <!-- Tarefas Recentes -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Atividade Recente</h3>
                
                @if($tarefasRecentes->count() > 0)
                    <div class="space-y-3">
                        @foreach($tarefasRecentes->take(5) as $tarefa)
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @php
                                        $statusColors = [
                                            'pendente' => 'bg-gray-400',
                                            'em_andamento' => 'bg-blue-400',
                                            'concluida' => 'bg-green-400',
                                            'cancelada' => 'bg-red-400'
                                        ];
                                    @endphp
                                    <div class="w-2 h-2 {{ $statusColors[$tarefa->status] ?? 'bg-gray-400' }} rounded-full"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $tarefa->titulo }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ ucfirst(str_replace('_', ' ', $tarefa->status)) }} • 
                                        {{ $tarefa->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                @if($tarefa->status == 'pendente')
                                    <form action="{{ route('tarefa.iniciar', $tarefa) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded">
                                            Iniciar
                                        </button>
                                    </form>
                                @elseif($tarefa->status == 'em_andamento')
                                    <a href="{{ route('tarefa.detalhes', $tarefa) }}" class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded">
                                        Ver
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Nenhuma tarefa recente.</p>
                @endif
            </div>
        </div>

        <!-- Google Calendar -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Agendamentos do Google Calendar</h3>
                    @if($isGoogleConnected)
                        <form action="{{ route('google.disconnect') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                Desconectar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('google.auth') }}" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                            Conectar Google Calendar
                        </a>
                    @endif
                </div>
                
                @if($isGoogleConnected)
                    @if(count($googleEvents) > 0)
                        <div class="space-y-3">
                            @foreach($googleEvents as $event)
                                <div class="border-l-4 border-purple-400 pl-4 py-2">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $event['summary'] ?? 'Sem título' }}</h4>
                                            @if(isset($event['location']))
                                                <p class="text-xs text-gray-500">Local: {{ $event['location'] }}</p>
                                            @endif
                                            <p class="text-xs text-gray-500">
                                                @if($event['is_all_day'])
                                                    Dia todo - {{ \Carbon\Carbon::parse($event['start'])->format('d/m/Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($event['start'])->format('d/m/Y H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($event['end'])->format('H:i') }}
                                                @endif
                                            </p>
                                        </div>
                                        <a href="{{ $event['html_link'] }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                                            Ver no Google
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Nenhum agendamento nos próximos 30 dias.</p>
                    @endif
                @else
                    <p class="text-gray-500 text-sm">Conecte sua conta Google para ver seus agendamentos.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Tarefas por Prioridade -->
    @if(!empty($tarefasPrioridade))
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Tarefas por Prioridade</h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach(['urgente' => 'Urgente', 'alta' => 'Alta', 'media' => 'Média', 'baixa' => 'Baixa'] as $key => $label)
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $tarefasPrioridade[$key] ?? 0 }}</div>
                        <div class="text-sm text-gray-500">{{ $label }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection