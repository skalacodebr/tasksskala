@extends('layouts.cliente')

@section('title', 'Novo Feedback')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <a href="{{ route('cliente.feedbacks') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Novo Feedback</h1>
                    <p class="text-gray-600 mt-1">Compartilhe suas sugestões, reclamações ou elogios</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário -->
    <form action="{{ route('cliente.feedback.armazenar') }}" method="POST">
        @csrf
        
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6 space-y-6">
                <!-- Tipo de Feedback -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Feedback <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        <!-- Sugestão -->
                        <label class="relative">
                            <input type="radio" name="tipo" value="sugestao" class="sr-only peer" required>
                            <div class="flex flex-col items-center p-4 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                <svg class="w-8 h-8 mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                <span class="text-sm font-medium">Sugestão</span>
                            </div>
                        </label>

                        <!-- Reclamação -->
                        <label class="relative">
                            <input type="radio" name="tipo" value="reclamacao" class="sr-only peer" required>
                            <div class="flex flex-col items-center p-4 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 peer-checked:border-red-500 peer-checked:bg-red-50">
                                <svg class="w-8 h-8 mb-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">Reclamação</span>
                            </div>
                        </label>

                        <!-- Elogio -->
                        <label class="relative">
                            <input type="radio" name="tipo" value="elogio" class="sr-only peer" required>
                            <div class="flex flex-col items-center p-4 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 peer-checked:border-green-500 peer-checked:bg-green-50">
                                <svg class="w-8 h-8 mb-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                <span class="text-sm font-medium">Elogio</span>
                            </div>
                        </label>

                        <!-- Dúvida -->
                        <label class="relative">
                            <input type="radio" name="tipo" value="duvida" class="sr-only peer" required>
                            <div class="flex flex-col items-center p-4 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 peer-checked:border-purple-500 peer-checked:bg-purple-50">
                                <svg class="w-8 h-8 mb-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">Dúvida</span>
                            </div>
                        </label>

                        <!-- Outro -->
                        <label class="relative">
                            <input type="radio" name="tipo" value="outro" class="sr-only peer" required>
                            <div class="flex flex-col items-center p-4 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 peer-checked:border-gray-500 peer-checked:bg-gray-100">
                                <svg class="w-8 h-8 mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span class="text-sm font-medium">Outro</span>
                            </div>
                        </label>
                    </div>
                    @error('tipo')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prioridade -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Prioridade <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <!-- Baixa -->
                        <label class="relative">
                            <input type="radio" name="prioridade" value="baixa" class="sr-only peer" required>
                            <div class="flex items-center justify-center p-3 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 peer-checked:border-green-500 peer-checked:bg-green-50">
                                <span class="text-sm font-medium text-green-700">Baixa</span>
                            </div>
                        </label>

                        <!-- Média -->
                        <label class="relative">
                            <input type="radio" name="prioridade" value="media" class="sr-only peer" checked required>
                            <div class="flex items-center justify-center p-3 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                <span class="text-sm font-medium text-blue-700">Média</span>
                            </div>
                        </label>

                        <!-- Alta -->
                        <label class="relative">
                            <input type="radio" name="prioridade" value="alta" class="sr-only peer" required>
                            <div class="flex items-center justify-center p-3 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 peer-checked:border-yellow-500 peer-checked:bg-yellow-50">
                                <span class="text-sm font-medium text-yellow-700">Alta</span>
                            </div>
                        </label>

                        <!-- Urgente -->
                        <label class="relative">
                            <input type="radio" name="prioridade" value="urgente" class="sr-only peer" required>
                            <div class="flex items-center justify-center p-3 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 peer-checked:border-red-500 peer-checked:bg-red-50">
                                <span class="text-sm font-medium text-red-700">Urgente</span>
                            </div>
                        </label>
                    </div>
                    @error('prioridade')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Projeto Relacionado -->
                <div>
                    <label for="projeto_id" class="block text-sm font-medium text-gray-700">
                        Projeto Relacionado
                    </label>
                    <select name="projeto_id" id="projeto_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Nenhum projeto específico</option>
                        @foreach($projetos as $projeto)
                            <option value="{{ $projeto->id }}">{{ $projeto->nome }}</option>
                        @endforeach
                    </select>
                    @error('projeto_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assunto -->
                <div>
                    <label for="assunto" class="block text-sm font-medium text-gray-700">
                        Assunto <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="assunto" 
                           id="assunto" 
                           required
                           maxlength="255"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="Resuma em poucas palavras">
                    @error('assunto')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mensagem -->
                <div>
                    <label for="mensagem" class="block text-sm font-medium text-gray-700">
                        Mensagem <span class="text-red-500">*</span>
                    </label>
                    <textarea name="mensagem" 
                              id="mensagem" 
                              rows="6" 
                              required
                              minlength="10"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Descreva detalhadamente seu feedback..."></textarea>
                    @error('mensagem')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Ações -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="submit" class="w-full sm:w-auto sm:ml-3 inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Enviar Feedback
                </button>
                <a href="{{ route('cliente.feedbacks') }}" class="mt-3 w-full sm:w-auto sm:mt-0 inline-flex justify-center px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    // Adicionar contador de caracteres para a mensagem
    const textarea = document.getElementById('mensagem');
    const charCount = document.createElement('div');
    charCount.className = 'text-sm text-gray-500 mt-1';
    textarea.parentNode.appendChild(charCount);
    
    function updateCharCount() {
        const length = textarea.value.length;
        charCount.textContent = `${length} caracteres`;
    }
    
    textarea.addEventListener('input', updateCharCount);
    updateCharCount();
</script>
@endsection