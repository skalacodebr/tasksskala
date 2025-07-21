@extends('layouts.cliente')

@section('title', 'Meus Feedbacks')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Meus Feedbacks</h1>
                    <p class="text-gray-600 mt-1">Acompanhe suas sugestões e reclamações</p>
                </div>
                <a href="{{ route('cliente.feedback.criar') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Novo Feedback
                </a>
            </div>
        </div>
    </div>

    @if($feedbacks->count() > 0)
        <!-- Lista de Feedbacks -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($feedbacks as $feedback)
                <li>
                    <a href="{{ route('cliente.feedback.show', $feedback) }}" class="block hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center flex-1">
                                    <!-- Ícone do Tipo -->
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-full flex items-center justify-center
                                                    @if($feedback->tipo == 'reclamacao') bg-red-100
                                                    @elseif($feedback->tipo == 'sugestao') bg-blue-100
                                                    @elseif($feedback->tipo == 'elogio') bg-green-100
                                                    @elseif($feedback->tipo == 'duvida') bg-purple-100
                                                    @else bg-gray-100 @endif">
                                            @if($feedback->tipo == 'reclamacao')
                                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @elseif($feedback->tipo == 'sugestao')
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                                </svg>
                                            @elseif($feedback->tipo == 'elogio')
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                </svg>
                                            @elseif($feedback->tipo == 'duvida')
                                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-lg font-medium text-gray-900 truncate">
                                                {{ $feedback->assunto }}
                                            </p>
                                            <div class="ml-2 flex items-center space-x-2">
                                                <!-- Prioridade -->
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                      style="background-color: {{ $feedback->cor_prioridade }}20; color: {{ $feedback->cor_prioridade }};">
                                                    {{ ucfirst($feedback->prioridade) }}
                                                </span>
                                                
                                                <!-- Status -->
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                           @if($feedback->status == 'pendente') bg-yellow-100 text-yellow-800
                                                           @elseif($feedback->status == 'em_analise') bg-blue-100 text-blue-800
                                                           @elseif($feedback->status == 'respondido') bg-green-100 text-green-800
                                                           @elseif($feedback->status == 'resolvido') bg-purple-100 text-purple-800
                                                           @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $feedback->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <span class="truncate">{{ Str::limit($feedback->mensagem, 100) }}</span>
                                        </div>
                                        
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            @if($feedback->projeto)
                                                <span class="mr-4">
                                                    <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                    </svg>
                                                    {{ $feedback->projeto->nome }}
                                                </span>
                                            @endif
                                            <span>
                                                <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $feedback->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                        
                                        @if($feedback->respondido_em)
                                            <div class="mt-1 text-sm text-green-600">
                                                <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Respondido em {{ $feedback->respondido_em->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Avaliação -->
                                @if($feedback->avaliacao)
                                    <div class="ml-4 flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $feedback->avaliacao ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.007 3.104a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                @endif
                                
                                <div class="ml-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        
        <!-- Paginação -->
        @if($feedbacks->hasPages())
            <div class="mt-6">
                {{ $feedbacks->links() }}
            </div>
        @endif
    @else
        <!-- Estado Vazio -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-8 sm:p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum feedback enviado</h3>
                <p class="text-gray-600 mb-4">Compartilhe suas sugestões ou reclamações conosco.</p>
                <a href="{{ route('cliente.feedback.criar') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Enviar Primeiro Feedback
                </a>
            </div>
        </div>
    @endif
</div>
@endsection