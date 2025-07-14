@extends('layouts.admin')

@section('title', 'Novo Projeto')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Criar Novo Projeto</h3>
            
            <form action="{{ route('admin.projetos.store') }}" method="POST" id="projeto-form">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div class="lg:col-span-2">
                        <label for="nome" class="block text-sm font-medium text-gray-700">Nome do Projeto</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('nome') border-red-500 @enderror">
                        @error('nome')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div class="lg:col-span-2">
                        <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea name="descricao" id="descricao" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('descricao') border-red-500 @enderror">{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Repositório Git -->
                    <div>
                        <label for="repositorio_git" class="block text-sm font-medium text-gray-700">Repositório Git (URL)</label>
                        <input type="url" name="repositorio_git" id="repositorio_git" value="{{ old('repositorio_git') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('repositorio_git') border-red-500 @enderror">
                        @error('repositorio_git')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                            <option value="em_andamento" {{ old('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="aprovacao_app" {{ old('status') == 'aprovacao_app' ? 'selected' : '' }}>Aprovação App</option>
                            <option value="pausado" {{ old('status') == 'pausado' ? 'selected' : '' }}>Pausado</option>
                            <option value="concluido" {{ old('status') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                            <option value="cancelado" {{ old('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cliente -->
                    <div>
                        <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                        <select name="cliente_id" id="cliente_id" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('cliente_id') border-red-500 @enderror">
                            <option value="">Selecione um cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id', request('cliente_id')) == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Colaborador Responsável -->
                    <div>
                        <label for="colaborador_responsavel_id" class="block text-sm font-medium text-gray-700">Colaborador Responsável</label>
                        <select name="colaborador_responsavel_id" id="colaborador_responsavel_id" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('colaborador_responsavel_id') border-red-500 @enderror">
                            <option value="">Selecione um colaborador</option>
                            @foreach($colaboradores as $colaborador)
                                <option value="{{ $colaborador->id }}" {{ old('colaborador_responsavel_id') == $colaborador->id ? 'selected' : '' }}>
                                    {{ $colaborador->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('colaborador_responsavel_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prazo -->
                    <div>
                        <label for="prazo" class="block text-sm font-medium text-gray-700">Prazo</label>
                        <input type="date" name="prazo" id="prazo" value="{{ old('prazo') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('prazo') border-red-500 @enderror">
                        @error('prazo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Anotações -->
                    <div class="lg:col-span-2">
                        <label for="anotacoes" class="block text-sm font-medium text-gray-700">Anotações</label>
                        <textarea name="anotacoes" id="anotacoes" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('anotacoes') border-red-500 @enderror">{{ old('anotacoes') }}</textarea>
                        @error('anotacoes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Marcos do Projeto -->
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-medium text-gray-900">Marcos do Projeto</h4>
                        <button type="button" id="adicionar-marco" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Adicionar Marco
                        </button>
                    </div>
                    
                    <div id="marcos-container">
                        <!-- Marcos serão adicionados dinamicamente aqui -->
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.projetos.index') }}" 
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700">
                        Criar Projeto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let marcoIndex = 0;
    const marcosContainer = document.getElementById('marcos-container');
    const adicionarMarcoBtn = document.getElementById('adicionar-marco');

    function criarMarco(index) {
        return `
            <div class="marco-item bg-gray-50 p-4 rounded-lg mb-4" data-index="${index}">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="font-medium text-gray-900">Marco ${index + 1}</h5>
                    <button type="button" class="remover-marco text-red-600 hover:text-red-900 text-sm">
                        Remover
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nome do Marco</label>
                        <input type="text" name="marcos[${index}][nome]" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Prazo</label>
                        <input type="date" name="marcos[${index}][prazo]" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Valor (R$)</label>
                        <input type="number" name="marcos[${index}][valor]" step="0.01" min="0"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea name="marcos[${index}][descricao]" rows="2"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>
            </div>
        `;
    }

    adicionarMarcoBtn.addEventListener('click', function() {
        marcosContainer.insertAdjacentHTML('beforeend', criarMarco(marcoIndex));
        marcoIndex++;
    });

    marcosContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remover-marco')) {
            e.target.closest('.marco-item').remove();
        }
    });
});
</script>
@endsection