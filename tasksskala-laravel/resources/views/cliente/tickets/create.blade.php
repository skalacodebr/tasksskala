@extends('layouts.cliente')

@section('title', 'Abrir Novo Ticket')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Abrir Novo Ticket</h2>
        <p class="text-gray-600 mt-1">Descreva seu problema ou solicitação e nossa equipe entrará em contato.</p>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('cliente.tickets.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                    Título <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="titulo" 
                       id="titulo" 
                       value="{{ old('titulo') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('titulo') border-red-500 @enderror"
                       placeholder="Resumo do problema ou solicitação"
                       required>
                @error('titulo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="setor" class="block text-sm font-medium text-gray-700 mb-2">
                    Setor <span class="text-red-500">*</span>
                </label>
                <select name="setor" 
                        id="setor" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('setor') border-red-500 @enderror"
                        required>
                    <option value="">Selecione o setor</option>
                    <option value="comercial" {{ old('setor') == 'comercial' ? 'selected' : '' }}>Comercial</option>
                    <option value="financeiro" {{ old('setor') == 'financeiro' ? 'selected' : '' }}>Financeiro</option>
                    <option value="desenvolvimento" {{ old('setor') == 'desenvolvimento' ? 'selected' : '' }}>Desenvolvimento</option>
                </select>
                @error('setor')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="prioridade" class="block text-sm font-medium text-gray-700 mb-2">
                    Prioridade <span class="text-red-500">*</span>
                </label>
                <select name="prioridade" 
                        id="prioridade" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('prioridade') border-red-500 @enderror"
                        required>
                    <option value="baixa" {{ old('prioridade', 'media') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                    <option value="media" {{ old('prioridade', 'media') == 'media' ? 'selected' : '' }}>Média</option>
                    <option value="alta" {{ old('prioridade') == 'alta' ? 'selected' : '' }}>Alta</option>
                </select>
                @error('prioridade')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if($projetos->count() > 0)
            <div class="mb-4">
                <label for="projeto_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Projeto Relacionado
                </label>
                <select name="projeto_id" 
                        id="projeto_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('projeto_id') border-red-500 @enderror">
                    <option value="">Nenhum projeto específico</option>
                    @foreach($projetos as $projeto)
                        <option value="{{ $projeto->id }}" {{ old('projeto_id') == $projeto->id ? 'selected' : '' }}>
                            {{ $projeto->nome }}
                        </option>
                    @endforeach
                </select>
                @error('projeto_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <div class="mb-6">
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                    Descrição <span class="text-red-500">*</span>
                </label>
                <textarea name="descricao" 
                          id="descricao" 
                          rows="6" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('descricao') border-red-500 @enderror"
                          placeholder="Descreva detalhadamente seu problema ou solicitação..."
                          required>{{ old('descricao') }}</textarea>
                @error('descricao')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between">
                <a href="{{ route('cliente.tickets.index') }}" class="text-gray-600 hover:text-gray-800">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                    Abrir Ticket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection