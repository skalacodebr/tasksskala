@extends('layouts.cliente')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Ticket #{{ $ticket->id }}</h2>
                <p class="text-lg text-gray-700 mt-1">{{ $ticket->titulo }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $ticket->status_color }}">
                    {{ $ticket->status_label }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $ticket->prioridade_color }}">
                    {{ $ticket->prioridade_label }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Setor</p>
                    <p class="font-medium">{{ $ticket->setor_label }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Criado em</p>
                    <p class="font-medium">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($ticket->projeto)
                <div>
                    <p class="text-sm text-gray-500">Projeto</p>
                    <p class="font-medium">{{ $ticket->projeto->nome }}</p>
                </div>
                @endif
                @if($ticket->atribuidoPara)
                <div>
                    <p class="text-sm text-gray-500">Atribuído para</p>
                    <p class="font-medium">{{ $ticket->atribuidoPara->nome }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Histórico de Mensagens</h3>
            
            <div class="space-y-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-2">
                        <p class="font-medium text-gray-900">{{ $ticket->cliente->nome }}</p>
                        <p class="text-sm text-gray-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->descricao }}</p>
                </div>

                @foreach($ticket->mensagens->where('is_internal', false) as $mensagem)
                <div class="{{ $mensagem->isFromCliente() ? 'bg-gray-50' : 'bg-blue-50' }} rounded-lg p-4">
                    <div class="flex justify-between items-start mb-2">
                        <p class="font-medium {{ $mensagem->isFromCliente() ? 'text-gray-900' : 'text-blue-900' }}">
                            {{ $mensagem->autor }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $mensagem->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <p class="{{ $mensagem->isFromCliente() ? 'text-gray-700' : 'text-blue-700' }} whitespace-pre-wrap">{{ $mensagem->mensagem }}</p>
                </div>
                @endforeach
            </div>

            @if($ticket->status !== 'fechado')
            <form action="{{ route('cliente.tickets.reply', $ticket) }}" method="POST" class="border-t pt-4">
                @csrf
                <div class="mb-4">
                    <label for="mensagem" class="block text-sm font-medium text-gray-700 mb-2">
                        Adicionar Mensagem
                    </label>
                    <textarea name="mensagem" 
                              id="mensagem" 
                              rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('mensagem') border-red-500 @enderror"
                              placeholder="Digite sua mensagem..."
                              required></textarea>
                    @error('mensagem')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                        Enviar Mensagem
                    </button>
                </div>
            </form>
            @else
            <div class="border-t pt-4">
                <p class="text-gray-500 text-center">Este ticket está fechado e não aceita novas mensagens.</p>
            </div>
            @endif
        </div>
    </div>

    <div class="flex justify-between">
        <a href="{{ route('cliente.tickets.index') }}" class="text-gray-600 hover:text-gray-800">
            ← Voltar para Lista
        </a>
        @if($ticket->status !== 'fechado')
        <form action="{{ route('cliente.tickets.close', $ticket) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja fechar este ticket?')">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Fechar Ticket
            </button>
        </form>
        @endif
    </div>
</div>
@endsection