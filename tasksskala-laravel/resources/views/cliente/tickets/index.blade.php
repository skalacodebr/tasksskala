@extends('layouts.cliente')

@section('title', 'Tickets de Suporte')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Tickets de Suporte</h2>
        <a href="{{ route('cliente.tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Abrir Novo Ticket
        </a>
    </div>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-md">
    <ul class="divide-y divide-gray-200">
        @forelse($tickets as $ticket)
        <li>
            <a href="{{ route('cliente.tickets.show', $ticket) }}" class="block hover:bg-gray-50">
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-lg font-medium text-blue-600 truncate">
                                        #{{ $ticket->id }} - {{ $ticket->titulo }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ Str::limit($ticket->descricao, 100) }}
                                    </p>
                                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                        <span>Setor: {{ $ticket->setor_label }}</span>
                                        @if($ticket->projeto)
                                            <span>Projeto: {{ $ticket->projeto->nome }}</span>
                                        @endif
                                        <span>{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                                        <span>{{ $ticket->mensagens->count() }} mensagen(s)</span>
                                    </div>
                                </div>
                                <div class="ml-4 flex flex-col items-end space-y-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->status_color }}">
                                        {{ $ticket->status_label }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->prioridade_color }}">
                                        {{ $ticket->prioridade_label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        @empty
        <li class="px-4 py-8 text-center">
            <p class="text-gray-500 mb-4">Você ainda não abriu nenhum ticket de suporte.</p>
            <a href="{{ route('cliente.tickets.create') }}" class="text-blue-600 hover:text-blue-500">
                Abrir primeiro ticket
            </a>
        </li>
        @endforelse
    </ul>
</div>

@if($tickets->hasPages())
<div class="mt-6">
    {{ $tickets->links() }}
</div>
@endif
@endsection