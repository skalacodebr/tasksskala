@extends('layouts.admin')

@section('title', 'Editar Projeto')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Editar Projeto</h3>
            
            <form action="{{ route('admin.projetos.update', $projeto) }}" method="POST" id="projeto-form">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div class="lg:col-span-2">
                        <label for="nome" class="block text-sm font-medium text-gray-700">Nome do Projeto</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome', $projeto->nome) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('nome') border-red-500 @enderror">
                        @error('nome')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div class="lg:col-span-2">
                        <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição (Opcional)</label>
                        <textarea name="descricao" id="descricao" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('descricao') border-red-500 @enderror">{{ old('descricao', $projeto->descricao) }}</textarea>
                        @error('descricao')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Repositório Git -->
                    <div>
                        <label for="repositorio_git" class="block text-sm font-medium text-gray-700">Repositório Git (URL)</label>
                        <input type="url" name="repositorio_git" id="repositorio_git" value="{{ old('repositorio_git', $projeto->repositorio_git) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('repositorio_git') border-red-500 @enderror">
                        @error('repositorio_git')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status (Sistema)</label>
                        <select name="status" id="status" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                            <option value="em_andamento" {{ old('status', $projeto->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="aprovacao_app" {{ old('status', $projeto->status) == 'aprovacao_app' ? 'selected' : '' }}>Aprovação App</option>
                            <option value="pausado" {{ old('status', $projeto->status) == 'pausado' ? 'selected' : '' }}>Pausado</option>
                            <option value="concluido" {{ old('status', $projeto->status) == 'concluido' ? 'selected' : '' }}>Concluído</option>
                            <option value="cancelado" {{ old('status', $projeto->status) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Personalizado -->
                    <div>
                        <label for="status_id" class="block text-sm font-medium text-gray-700">Status Personalizado</label>
                        <select name="status_id" id="status_id" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('status_id') border-red-500 @enderror">
                            <option value="">Selecione um status (opcional)</option>
                            @foreach($statusProjetos as $statusProjeto)
                                <option value="{{ $statusProjeto->id }}" 
                                        {{ old('status_id', $projeto->status_id) == $statusProjeto->id ? 'selected' : '' }}
                                        data-cor="{{ $statusProjeto->cor }}">
                                    {{ $statusProjeto->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('status_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Status personalizado para melhor organização</p>
                    </div>

                    <!-- Cliente -->
                    <div>
                        <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                        <select name="cliente_id" id="cliente_id" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('cliente_id') border-red-500 @enderror">
                            <option value="">Selecione um cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id', $projeto->cliente_id) == $cliente->id ? 'selected' : '' }}>
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
                        <label for="colaborador_responsavel_id" class="block text-sm font-medium text-gray-700">Colaborador Responsável Principal</label>
                        <select name="colaborador_responsavel_id" id="colaborador_responsavel_id" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('colaborador_responsavel_id') border-red-500 @enderror">
                            <option value="">Selecione um colaborador</option>
                            @foreach($colaboradores as $colaborador)
                                <option value="{{ $colaborador->id }}" {{ old('colaborador_responsavel_id', $projeto->colaborador_responsavel_id) == $colaborador->id ? 'selected' : '' }}>
                                    {{ $colaborador->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('colaborador_responsavel_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Responsáveis Adicionais -->
                    <div class="lg:col-span-2">
                        <label for="responsaveis" class="block text-sm font-medium text-gray-700">Responsáveis Adicionais</label>
                        <select name="responsaveis[]" id="responsaveis" multiple
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('responsaveis') border-red-500 @enderror">
                            @foreach($colaboradores as $colaborador)
                                <option value="{{ $colaborador->id }}" 
                                    {{ (is_array(old('responsaveis')) && in_array($colaborador->id, old('responsaveis'))) || 
                                       (old('responsaveis') === null && $projeto->responsaveis->contains($colaborador->id)) ? 'selected' : '' }}>
                                    {{ $colaborador->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('responsaveis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Segure Ctrl/Cmd para selecionar múltiplos colaboradores</p>
                    </div>

                    <!-- Prazo -->
                    <div>
                        <label for="prazo" class="block text-sm font-medium text-gray-700">Prazo (Opcional)</label>
                        <input type="date" name="prazo" id="prazo" value="{{ old('prazo', $projeto->prazo ? $projeto->prazo->format('Y-m-d') : '') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('prazo') border-red-500 @enderror">
                        @error('prazo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Anotações -->
                    <div class="lg:col-span-2">
                        <label for="anotacoes" class="block text-sm font-medium text-gray-700">Anotações</label>
                        <textarea name="anotacoes" id="anotacoes" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('anotacoes') border-red-500 @enderror">{{ old('anotacoes', $projeto->anotacoes) }}</textarea>
                        @error('anotacoes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instruções Ambiente de Teste -->
                    <div class="lg:col-span-2">
                        <label for="instrucoes_ambiente_teste" class="block text-sm font-medium text-gray-700">Instruções para Acesso ao Ambiente de Teste</label>
                        <textarea name="instrucoes_ambiente_teste" id="instrucoes_ambiente_teste" rows="4" placeholder="Ex: URL, usuário, senha, informações específicas para acesso ao ambiente de teste..."
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('instrucoes_ambiente_teste') border-red-500 @enderror">{{ old('instrucoes_ambiente_teste', $projeto->instrucoes_ambiente_teste) }}</textarea>
                        @error('instrucoes_ambiente_teste')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Instruções que o cliente verá para acessar o ambiente de teste</p>
                    </div>

                    <!-- Instruções Ambiente de Produção -->
                    <div class="lg:col-span-2">
                        <label for="instrucoes_ambiente_producao" class="block text-sm font-medium text-gray-700">Instruções para Acesso ao Ambiente de Produção</label>
                        <textarea name="instrucoes_ambiente_producao" id="instrucoes_ambiente_producao" rows="4" placeholder="Ex: URL, usuário, senha, informações específicas para acesso ao ambiente de produção..."
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('instrucoes_ambiente_producao') border-red-500 @enderror">{{ old('instrucoes_ambiente_producao', $projeto->instrucoes_ambiente_producao) }}</textarea>
                        @error('instrucoes_ambiente_producao')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Instruções que o cliente verá para acessar o ambiente de produção</p>
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
                        @foreach($projeto->marcos as $index => $marco)
                            <div class="marco-item bg-gray-50 p-4 rounded-lg mb-4" data-index="{{ $index }}">
                                <input type="hidden" name="marcos[{{ $index }}][id]" value="{{ $marco->id }}">
                                
                                <div class="flex justify-between items-center mb-4">
                                    <h5 class="font-medium text-gray-900">Marco {{ $index + 1 }}</h5>
                                    <button type="button" class="remover-marco text-red-600 hover:text-red-900 text-sm">
                                        Remover
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nome do Marco</label>
                                        <input type="text" name="marcos[{{ $index }}][nome]" value="{{ $marco->nome }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prazo</label>
                                        <input type="date" name="marcos[{{ $index }}][prazo]" value="{{ $marco->prazo ? $marco->prazo->format('Y-m-d') : '' }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Valor (R$)</label>
                                        <input type="number" name="marcos[{{ $index }}][valor]" step="0.01" min="0" value="{{ $marco->valor }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <select name="marcos[{{ $index }}][status]" 
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            <option value="pendente" {{ $marco->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                            <option value="entregue" {{ $marco->status == 'entregue' ? 'selected' : '' }}>Entregue</option>
                                            <option value="aprovado" {{ $marco->status == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                                            <option value="rejeitado" {{ $marco->status == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                                        </select>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Descrição</label>
                                        <textarea name="marcos[{{ $index }}][descricao]" rows="2"
                                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $marco->descricao }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.projetos.index') }}" 
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700">
                        Atualizar Projeto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let marcoIndex = {{ $projeto->marcos->count() }};
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
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="marcos[${index}][status]" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="pendente">Pendente</option>
                            <option value="entregue">Entregue</option>
                            <option value="aprovado">Aprovado</option>
                            <option value="rejeitado">Rejeitado</option>
                        </select>
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