@extends('layouts.colaborador')

@section('title', 'Editar Projeto')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="card-dark shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-primary-dark">Editar Projeto</h1>
                    <p class="text-gray-400 mt-1">{{ $projeto->nome }}</p>
                </div>
                <a href="{{ route('projetos.show', $projeto) }}" class="bg-gray-300 hover:bg-gray-400 text-primary-dark font-bold py-2 px-4 rounded">
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Formulário -->
    <div class="card-dark shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('projetos.update', $projeto) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nome do Projeto -->
                <div>
                    <label for="nome" class="block text-sm font-medium text-muted-dark">
                        Nome do Projeto *
                    </label>
                    <input type="text" name="nome" id="nome" required 
                           value="{{ old('nome', $projeto->nome) }}"
                           class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('nome')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid de 2 colunas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Cliente -->
                    <div>
                        <label for="cliente_id" class="block text-sm font-medium text-muted-dark">
                            Cliente *
                        </label>
                        <select name="cliente_id" id="cliente_id" required
                                class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione um cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id', $projeto->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Responsável -->
                    <div>
                        <label for="responsavel_id" class="block text-sm font-medium text-muted-dark">
                            Responsável *
                        </label>
                        <select name="responsavel_id" id="responsavel_id" required
                                class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach($colaboradores as $colab)
                                <option value="{{ $colab->id }}" {{ old('responsavel_id', $projeto->colaborador_responsavel_id) == $colab->id ? 'selected' : '' }}>
                                    {{ $colab->nome }} @if($colab->id == $colaborador->id)(Você)@endif
                                </option>
                            @endforeach
                        </select>
                        @error('responsavel_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Descrição -->
                <div>
                    <label for="descricao" class="block text-sm font-medium text-muted-dark">
                        Descrição do Projeto
                    </label>
                    <textarea name="descricao" id="descricao" rows="4"
                              class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Descreva os objetivos e escopo do projeto...">{{ old('descricao', $projeto->descricao) }}</textarea>
                    @error('descricao')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid de 3 colunas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-muted-dark">
                            Status *
                        </label>
                        <select name="status" id="status" required
                                class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="planejamento" {{ old('status', $projeto->status) == 'planejamento' ? 'selected' : '' }}>Planejamento</option>
                            <option value="em_andamento" {{ old('status', $projeto->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="em_teste" {{ old('status', $projeto->status) == 'em_teste' ? 'selected' : '' }}>Em Teste</option>
                            <option value="aprovacao_app" {{ old('status', $projeto->status) == 'aprovacao_app' ? 'selected' : '' }}>Aprovação App</option>
                            <option value="concluido" {{ old('status', $projeto->status) == 'concluido' ? 'selected' : '' }}>Concluído</option>
                            <option value="cancelado" {{ old('status', $projeto->status) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prazo de Entrega -->
                    <div>
                        <label for="prazo_entrega" class="block text-sm font-medium text-muted-dark">
                            Prazo de Entrega
                        </label>
                        <input type="date" name="prazo_entrega" id="prazo_entrega"
                               value="{{ old('prazo_entrega', $projeto->prazo_entrega?->format('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('prazo_entrega')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link do Repositório -->
                    <div>
                        <label for="link_repositorio" class="block text-sm font-medium text-muted-dark">
                            Link do Repositório
                        </label>
                        <input type="url" name="link_repositorio" id="link_repositorio"
                               value="{{ old('link_repositorio', $projeto->link_repositorio) }}"
                               placeholder="https://github.com/..."
                               class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('link_repositorio')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Marcos do Projeto -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <label class="block text-sm font-medium text-muted-dark">
                            Marcos do Projeto
                        </label>
                        <button type="button" onclick="adicionarMarco()" 
                                class="btn-primary-dark text-sm font-bold py-1 px-3 rounded">
                            + Adicionar Marco
                        </button>
                    </div>
                    <div id="marcos-container" class="space-y-3">
                        <!-- Marcos existentes -->
                        @foreach($projeto->marcos as $marco)
                            <div class="flex items-center space-x-3 p-3 border border-gray-700 rounded-lg">
                                <div class="flex-1">
                                    <input type="text" 
                                           name="marcos[{{ $marco->id }}][nome]" 
                                           value="{{ $marco->nome }}"
                                           placeholder="Nome do marco"
                                           class="block w-full border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="w-48">
                                    <input type="date" 
                                           name="marcos[{{ $marco->id }}][prazo]" 
                                           value="{{ $marco->prazo?->format('Y-m-d') }}"
                                           class="block w-full border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="text-sm text-muted-dark w-24 text-center">
                                    Existente
                                </div>
                                <button type="button" onclick="removerMarco(this)" 
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded">
                                    ×
                                </button>
                            </div>
                        @endforeach
                        <!-- Novos marcos serão adicionados aqui -->
                    </div>
                </div>

                <!-- Anotações -->
                <div>
                    <label for="anotacoes" class="block text-sm font-medium text-muted-dark">
                        Anotações Gerais
                    </label>
                    <textarea name="anotacoes" id="anotacoes" rows="3"
                              class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Anotações importantes sobre o projeto...">{{ old('anotacoes', $projeto->anotacoes) }}</textarea>
                    @error('anotacoes')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Instruções para Ambientes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Instruções Ambiente de Teste -->
                    <div>
                        <label for="instrucoes_ambiente_teste" class="block text-sm font-medium text-muted-dark">
                            Instruções para Acesso ao Ambiente de Teste
                        </label>
                        <textarea name="instrucoes_ambiente_teste" id="instrucoes_ambiente_teste" rows="4"
                                  class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Ex: URL, usuário, senha, informações específicas para acesso ao ambiente de teste...">{{ old('instrucoes_ambiente_teste', $projeto->instrucoes_ambiente_teste) }}</textarea>
                        @error('instrucoes_ambiente_teste')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-muted-dark">Instruções que o cliente verá para acessar o ambiente de teste</p>
                    </div>

                    <!-- Instruções Ambiente de Produção -->
                    <div>
                        <label for="instrucoes_ambiente_producao" class="block text-sm font-medium text-muted-dark">
                            Instruções para Acesso ao Ambiente de Produção
                        </label>
                        <textarea name="instrucoes_ambiente_producao" id="instrucoes_ambiente_producao" rows="4"
                                  class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Ex: URL, usuário, senha, informações específicas para acesso ao ambiente de produção...">{{ old('instrucoes_ambiente_producao', $projeto->instrucoes_ambiente_producao) }}</textarea>
                        @error('instrucoes_ambiente_producao')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-muted-dark">Instruções que o cliente verá para acessar o ambiente de produção</p>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-700">
                    <a href="{{ route('projetos.show', $projeto) }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-primary-dark font-bold py-2 px-4 rounded">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="btn-primary-dark font-bold py-2 px-4 rounded">
                        Atualizar Projeto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let marcoIndex = {{ $projeto->marcos->count() + 1000 }}; // Usar número alto para evitar conflitos

function adicionarMarco() {
    const container = document.getElementById('marcos-container');
    const marcoDiv = document.createElement('div');
    marcoDiv.className = 'flex items-center space-x-3 p-3 border border-gray-700 rounded-lg';
    marcoDiv.innerHTML = `
        <div class="flex-1">
            <input type="text" 
                   name="marcos[novo_${marcoIndex}][nome]" 
                   placeholder="Nome do marco"
                   class="block w-full border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="w-48">
            <input type="date" 
                   name="marcos[novo_${marcoIndex}][prazo]" 
                   min="{{ now()->format('Y-m-d') }}"
                   class="block w-full border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="text-sm text-green-600 w-24 text-center">
            Novo
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
</script>
@endsection