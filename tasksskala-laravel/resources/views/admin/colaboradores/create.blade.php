@extends('layouts.admin')

@section('title', 'Novo Colaborador')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Criar Novo Colaborador</h3>
            
            <form action="{{ route('admin.colaboradores.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Nome -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('nome') border-red-500 @enderror">
                        @error('nome')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div>
                        <label for="senha" class="block text-sm font-medium text-gray-700">Senha</label>
                        <input type="password" name="senha" id="senha" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('senha') border-red-500 @enderror">
                        @error('senha')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Setor -->
                    <div>
                        <label for="setor_id" class="block text-sm font-medium text-gray-700">Setor</label>
                        <select name="setor_id" id="setor_id" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('setor_id') border-red-500 @enderror">
                            <option value="">Selecione um setor</option>
                            @foreach($setores as $setor)
                                <option value="{{ $setor->id }}" {{ old('setor_id') == $setor->id ? 'selected' : '' }}>
                                    {{ $setor->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('setor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- Novo Setor -->
                        <div class="mt-2">
                            <label for="novo_setor" class="block text-sm font-medium text-gray-600">Ou criar novo setor:</label>
                            <input type="text" name="novo_setor" id="novo_setor" value="{{ old('novo_setor') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Conhecimentos -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Conhecimentos</label>
                        <div class="max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                            @foreach($conhecimentos as $conhecimento)
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" name="conhecimentos[]" value="{{ $conhecimento->id }}" 
                                           id="conhecimento_{{ $conhecimento->id }}"
                                           {{ in_array($conhecimento->id, old('conhecimentos', [])) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label for="conhecimento_{{ $conhecimento->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $conhecimento->nome }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Novo Conhecimento -->
                        <div class="mt-2">
                            <label for="novo_conhecimento" class="block text-sm font-medium text-gray-600">Ou criar novo conhecimento:</label>
                            <input type="text" name="novo_conhecimento" id="novo_conhecimento" value="{{ old('novo_conhecimento') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.colaboradores.index') }}" 
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700">
                        Criar Colaborador
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection