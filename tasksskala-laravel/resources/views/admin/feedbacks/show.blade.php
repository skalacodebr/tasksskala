@extends('layouts.admin')

@section('title', 'Detalhes do Feedback')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.feedbacks.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Detalhes do Feedback</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Principais -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Detalhes do Feedback -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $feedback->assunto }}</h2>
                    <div class="flex items-center space-x-3">
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

                <!-- Mensagem do Cliente -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Mensagem do Cliente</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $feedback->mensagem }}</p>
                    </div>
                </div>

                <!-- Resposta -->
                @if($feedback->resposta)
                <div class="border-t pt-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Nossa Resposta</h3>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $feedback->resposta }}</p>
                        @if($feedback->respondidoPor)
                        <div class="mt-3 text-sm text-gray-600">
                            <span class="font-medium">Respondido por:</span> {{ $feedback->respondidoPor->nome }}
                            <span class="mx-2">•</span>
                            <span>{{ $feedback->respondido_em->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Avaliação -->
                @if($feedback->avaliacao)
                <div class="border-t pt-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Avaliação do Cliente</h3>
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-6 h-6 {{ $i <= $feedback->avaliacao ? 'text-yellow-400' : 'text-gray-300' }}" 
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.007 3.104a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                        <span class="ml-2 text-gray-600">{{ $feedback->avaliacao }}/5</span>
                    </div>
                </div>
                @endif

                <!-- Formulário de Resposta -->
                @if(!$feedback->resposta || $feedback->status == 'em_analise')
                <div class="border-t pt-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Responder Feedback</h3>
                    <form action="{{ route('admin.feedbacks.responder', $feedback) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="resposta" class="block text-sm font-medium text-gray-700 mb-1">
                                    Resposta <span class="text-red-500">*</span>
                                </label>
                                <textarea name="resposta" id="resposta" rows="6" required
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Digite sua resposta ao cliente...">{{ old('resposta', $feedback->resposta) }}</textarea>
                                @error('resposta')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                    Atualizar Status <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="em_analise" {{ $feedback->status == 'em_analise' ? 'selected' : '' }}>Em Análise</option>
                                    <option value="respondido" {{ $feedback->status == 'respondido' ? 'selected' : '' }}>Respondido</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Enviar Resposta
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informações do Cliente -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações do Cliente</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nome</p>
                        <p class="text-gray-900">{{ $feedback->cliente->nome }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Email</p>
                        <p class="text-gray-900">{{ $feedback->cliente->email }}</p>
                    </div>
                    @if($feedback->cliente->telefone)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Telefone</p>
                        <p class="text-gray-900">{{ $feedback->cliente->telefone }}</p>
                    </div>
                    @endif
                    @if($feedback->projeto)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Projeto</p>
                        <p class="text-gray-900">{{ $feedback->projeto->nome }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ações Rápidas</h3>
                <div class="space-y-3">
                    <!-- Atualizar Status -->
                    <form action="{{ route('admin.feedbacks.atualizarStatus', $feedback) }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="pendente" {{ $feedback->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="em_analise" {{ $feedback->status == 'em_analise' ? 'selected' : '' }}>Em Análise</option>
                                <option value="respondido" {{ $feedback->status == 'respondido' ? 'selected' : '' }}>Respondido</option>
                                <option value="resolvido" {{ $feedback->status == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                                <option value="arquivado" {{ $feedback->status == 'arquivado' ? 'selected' : '' }}>Arquivado</option>
                            </select>
                            <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Atualizar Status
                            </button>
                        </div>
                    </form>

                    <!-- Excluir -->
                    <form action="{{ route('admin.feedbacks.destroy', $feedback) }}" method="POST" 
                          onsubmit="return confirm('Tem certeza que deseja excluir este feedback?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Excluir Feedback
                        </button>
                    </form>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Timeline</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Criado</p>
                            <p class="text-sm text-gray-500">{{ $feedback->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($feedback->respondido_em)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-green-300 flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Respondido</p>
                            <p class="text-sm text-gray-500">{{ $feedback->respondido_em->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection