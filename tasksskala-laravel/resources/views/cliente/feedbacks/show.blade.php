@extends('layouts.cliente')

@section('title', 'Detalhes do Feedback')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('cliente.feedbacks') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $feedback->assunto }}</h1>
                        <div class="flex items-center space-x-4 mt-2">
                            <!-- Tipo -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($feedback->tipo == 'reclamacao') bg-red-100 text-red-800
                                        @elseif($feedback->tipo == 'sugestao') bg-blue-100 text-blue-800
                                        @elseif($feedback->tipo == 'elogio') bg-green-100 text-green-800
                                        @elseif($feedback->tipo == 'duvida') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($feedback->tipo) }}
                            </span>
                            
                            <!-- Prioridade -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                  style="background-color: {{ $feedback->cor_prioridade }}20; color: {{ $feedback->cor_prioridade }};">
                                Prioridade {{ ucfirst($feedback->prioridade) }}
                            </span>
                            
                            <!-- Status -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                       @if($feedback->status == 'pendente') bg-yellow-100 text-yellow-800
                                       @elseif($feedback->status == 'em_analise') bg-blue-100 text-blue-800
                                       @elseif($feedback->status == 'respondido') bg-green-100 text-green-800
                                       @elseif($feedback->status == 'resolvido') bg-purple-100 text-purple-800
                                       @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $feedback->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações do Feedback -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <!-- Metadados -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">Data de Envio</p>
                    <p class="mt-1 text-gray-900">{{ $feedback->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                @if($feedback->projeto)
                <div>
                    <p class="text-sm font-medium text-gray-500">Projeto Relacionado</p>
                    <p class="mt-1 text-gray-900">{{ $feedback->projeto->nome }}</p>
                </div>
                @endif
                
                @if($feedback->respondido_em)
                <div>
                    <p class="text-sm font-medium text-gray-500">Respondido em</p>
                    <p class="mt-1 text-gray-900">{{ $feedback->respondido_em->format('d/m/Y H:i') }}</p>
                </div>
                @endif
            </div>

            <!-- Mensagem -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Sua Mensagem</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $feedback->mensagem }}</p>
                </div>
            </div>

            <!-- Resposta -->
            @if($feedback->resposta)
            <div class="border-t pt-6 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Nossa Resposta</h3>
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $feedback->resposta }}</p>
                    @if($feedback->respondidoPor)
                    <p class="text-sm text-gray-500 mt-3">
                        Respondido por: {{ $feedback->respondidoPor->nome }}
                    </p>
                    @endif
                </div>

                <!-- Avaliação -->
                @if($feedback->status == 'respondido' && !$feedback->avaliacao)
                <div class="mt-6 bg-yellow-50 rounded-lg p-4">
                    <h4 class="text-lg font-medium text-gray-900 mb-3">Avalie nossa resposta</h4>
                    <form action="{{ route('cliente.feedback.avaliar', $feedback) }}" method="POST">
                        @csrf
                        <div class="flex items-center space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="avaliacao" value="{{ $i }}" class="sr-only peer" required>
                                <svg class="w-8 h-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.007 3.104a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </label>
                            @endfor
                        </div>
                        <button type="submit" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Enviar Avaliação
                        </button>
                    </form>
                </div>
                @elseif($feedback->avaliacao)
                <div class="mt-6 bg-green-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Sua avaliação:</p>
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-6 h-6 {{ $i <= $feedback->avaliacao ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.007 3.104a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @endfor
                        <span class="ml-2 text-sm text-gray-600">Obrigado pela sua avaliação!</span>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Ações -->
            @if($feedback->status == 'pendente' || $feedback->status == 'em_analise')
            <div class="border-t pt-6 mt-6">
                <p class="text-sm text-gray-600">
                    <svg class="inline-block w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Estamos analisando seu feedback. Em breve você receberá uma resposta.
                </p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Script para avaliação com estrelas interativas
    const stars = document.querySelectorAll('label svg');
    stars.forEach((star, index) => {
        star.addEventListener('mouseenter', () => {
            stars.forEach((s, i) => {
                if (i <= index) {
                    s.classList.add('text-yellow-400');
                    s.classList.remove('text-gray-300');
                } else {
                    s.classList.add('text-gray-300');
                    s.classList.remove('text-yellow-400');
                }
            });
        });
    });

    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('mouseleave', () => {
            const checkedInput = document.querySelector('input[name="avaliacao"]:checked');
            if (!checkedInput) {
                stars.forEach(star => {
                    star.classList.add('text-gray-300');
                    star.classList.remove('text-yellow-400');
                });
            }
        });
    }
</script>
@endsection