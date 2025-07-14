@extends('layouts.admin')

@section('title', 'Novo Status de Projeto')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Criar Novo Status de Projeto</h3>
            
            <form action="{{ route('admin.status-projetos.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Nome -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700">Nome *</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('nome') border-red-500 @enderror"
                               required>
                        @error('nome')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cor -->
                    <div>
                        <label for="cor" class="block text-sm font-medium text-gray-700">Cor *</label>
                        <div class="mt-1 flex items-center space-x-3">
                            <input type="color" name="cor" id="cor" value="{{ old('cor', '#3B82F6') }}" 
                                   class="h-10 w-20 border border-gray-300 rounded cursor-pointer @error('cor') border-red-500 @enderror">
                            <input type="text" name="cor_text" id="cor_text" value="{{ old('cor', '#3B82F6') }}" 
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="#3B82F6" readonly>
                        </div>
                        @error('cor')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ordem -->
                    <div>
                        <label for="ordem" class="block text-sm font-medium text-gray-700">Ordem</label>
                        <input type="number" name="ordem" id="ordem" value="{{ old('ordem', 0) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('ordem') border-red-500 @enderror"
                               min="0">
                        @error('ordem')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Define a ordem de exibição do status na lista</p>
                    </div>

                    <!-- Ativo -->
                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" name="ativo" id="ativo" value="1" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   {{ old('ativo', true) ? 'checked' : '' }}>
                            <label for="ativo" class="ml-2 block text-sm text-gray-900">
                                Status ativo
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Status inativos não aparecerão na lista de seleção</p>
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea name="descricao" id="descricao" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('descricao') border-red-500 @enderror"
                                  placeholder="Descreva quando este status deve ser usado...">{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.status-projetos.index') }}" 
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700">
                        Criar Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('cor').addEventListener('change', function() {
    document.getElementById('cor_text').value = this.value;
});

document.getElementById('cor_text').addEventListener('input', function() {
    document.getElementById('cor').value = this.value;
});
</script>
@endsection