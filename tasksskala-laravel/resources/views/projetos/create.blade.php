@extends('layouts.colaborador')

@section('title', 'Criar Projeto')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Criar Novo Projeto</h1>
                    <p class="text-gray-600 mt-1">Preencha as informações do projeto</p>
                </div>
                <a href="{{ route('projetos.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Formulário -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('projetos.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nome do Projeto -->
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700">
                        Nome do Projeto *
                    </label>
                    <input type="text" name="nome" id="nome" required 
                           value="{{ old('nome') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('nome')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid de 2 colunas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Cliente -->
                    <div>
                        <label for="cliente_id" class="block text-sm font-medium text-gray-700">
                            Cliente *
                        </label>
                        <select name="cliente_id" id="cliente_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione um cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Responsável -->
                    <div>
                        <label for="responsavel_id" class="block text-sm font-medium text-gray-700">
                            Responsável *
                        </label>
                        <select name="responsavel_id" id="responsavel_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach($colaboradores as $colab)
                                <option value="{{ $colab->id }}" {{ old('responsavel_id', $colaborador->id) == $colab->id ? 'selected' : '' }}>
                                    {{ $colab->nome }} @if($colab->id == $colaborador->id)(Você)@endif
                                </option>
                            @endforeach
                        </select>
                        @error('responsavel_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Descrição -->
                <div>
                    <label for="descricao" class="block text-sm font-medium text-gray-700">
                        Descrição do Projeto
                    </label>
                    <textarea name="descricao" id="descricao" rows="4"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Descreva os objetivos e escopo do projeto...">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid de 3 colunas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Status *
                        </label>
                        <select name="status" id="status" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="planejamento" {{ old('status', 'planejamento') == 'planejamento' ? 'selected' : '' }}>Planejamento</option>
                            <option value="em_andamento" {{ old('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="em_teste" {{ old('status') == 'em_teste' ? 'selected' : '' }}>Em Teste</option>
                            <option value="aprovacao_app" {{ old('status') == 'aprovacao_app' ? 'selected' : '' }}>Aprovação App</option>
                            <option value="concluido" {{ old('status') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                            <option value="cancelado" {{ old('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prazo de Entrega -->
                    <div>
                        <label for="prazo_entrega" class="block text-sm font-medium text-gray-700">
                            Prazo de Entrega
                        </label>
                        <input type="date" name="prazo_entrega" id="prazo_entrega"
                               value="{{ old('prazo_entrega') }}"
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('prazo_entrega')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link do Repositório -->
                    <div>
                        <label for="link_repositorio" class="block text-sm font-medium text-gray-700">
                            Link do Repositório
                        </label>
                        <input type="url" name="link_repositorio" id="link_repositorio"
                               value="{{ old('link_repositorio') }}"
                               placeholder="https://github.com/..."
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('link_repositorio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Marcos do Projeto -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Marcos do Projeto
                        </label>
                        <button type="button" onclick="adicionarMarco()" 
                                class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-1 px-3 rounded">
                            + Adicionar Marco
                        </button>
                    </div>
                    <div id="marcos-container" class="space-y-3">
                        <!-- Marcos serão adicionados dinamicamente aqui -->
                    </div>
                </div>

                <!-- Anotações -->
                <div>
                    <label for="anotacoes" class="block text-sm font-medium text-gray-700">
                        Anotações Gerais
                    </label>
                    <textarea name="anotacoes" id="anotacoes" rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Anotações importantes sobre o projeto...">{{ old('anotacoes') }}</textarea>
                    @error('anotacoes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botões de Ação -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('projetos.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Criar Projeto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let marcoIndex = 0;

function adicionarMarco() {
    const container = document.getElementById('marcos-container');
    const marcoDiv = document.createElement('div');
    marcoDiv.className = 'flex items-center space-x-3 p-3 border border-gray-200 rounded-lg';
    marcoDiv.innerHTML = `
        <div class="flex-1">
            <input type="text" 
                   name="marcos[${marcoIndex}][nome]" 
                   placeholder="Nome do marco"
                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="w-48">
            <input type="date" 
                   name="marcos[${marcoIndex}][prazo]" 
                   min="{{ now()->format('Y-m-d') }}"
                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <button type="button" onclick="removerMarco(this)" 
                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded">
            ×
        </button>
    `;
    container.appendChild(marcoDiv);
    marcoIndex++;
}

function removerMarco(button) {
    button.closest('div').remove();
}

// Adicionar um marco inicial
document.addEventListener('DOMContentLoaded', function() {
    adicionarMarco();
});
</script>
@endsection