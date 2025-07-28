@extends('layouts.colaborador')

@section('title', 'Criar Nova Tarefa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Criar Nova Tarefa</h1>
                    <p class="text-gray-600 mt-1">Crie uma nova tarefa para você ou para outros colaboradores</p>
                </div>
                <a href="{{ route('minhas-tarefas') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Formulário -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('tarefa.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Opção de criar múltiplas tarefas -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="multiplas_tarefas" id="multiplas_tarefas" value="1" 
                               {{ old('multiplas_tarefas') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="multiplas_tarefas" class="ml-2 block text-sm text-gray-900 font-medium">
                            Criar múltiplas tarefas para o mesmo projeto
                        </label>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">Marque esta opção para criar várias tarefas de uma vez. Cada linha de descrição criará uma tarefa separada.</p>
                </div>

                <!-- Título - Modo único -->
                <div id="titulo-unico">
                    <label for="titulo" class="block text-sm font-medium text-gray-700">
                        Título da Tarefa *
                    </label>
                    <input type="text" name="titulo" id="titulo" required 
                           value="{{ old('titulo') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('titulo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Título Base - Modo múltiplo -->
                <div id="titulo-multiplo" class="hidden">
                    <label for="titulo_base" class="block text-sm font-medium text-gray-700">
                        Título Base (Opcional)
                    </label>
                    <input type="text" name="titulo_base" id="titulo_base"
                           value="{{ old('titulo_base') }}"
                           placeholder="Ex: Implementar funcionalidade"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-sm text-gray-500">Se deixado em branco, o título será extraído da descrição de cada tarefa</p>
                </div>

                <!-- Grid de 2 colunas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Responsável -->
                    <div>
                        <label for="colaborador_id" class="block text-sm font-medium text-gray-700">
                            Responsável *
                        </label>
                        <select name="colaborador_id" id="colaborador_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach($colaboradores as $colab)
                                <option value="{{ $colab->id }}" {{ old('colaborador_id', $colaborador->id) == $colab->id ? 'selected' : '' }}>
                                    {{ $colab->nome }} @if($colab->id == $colaborador->id)(Você)@endif
                                </option>
                            @endforeach
                        </select>
                        @error('colaborador_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Projeto (Opcional) -->
                    <div>
                        <label for="projeto_id" class="block text-sm font-medium text-gray-700">
                            Projeto (Opcional)
                        </label>
                        <select name="projeto_id" id="projeto_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sem projeto específico</option>
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
                </div>

                <!-- Descrição - Modo único -->
                <div id="descricao-unica">
                    <label for="descricao" class="block text-sm font-medium text-gray-700">
                        Descrição
                    </label>
                    <textarea name="descricao" id="descricao" rows="4"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Descreva em detalhes o que precisa ser feito...">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrições Múltiplas -->
                <div id="descricoes-multiplas" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tarefas * <span class="text-gray-500">(uma por linha)</span>
                    </label>
                    <div id="tarefas-container" class="space-y-3">
                        <div class="tarefa-item">
                            <div class="flex gap-2">
                                <textarea name="descricoes[]" rows="2"
                                          class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Descrição da tarefa..." required></textarea>
                                <input type="date" name="prazos[]" 
                                       class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Prazo (opcional)">
                                <button type="button" class="remover-tarefa px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 hidden">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="adicionar-tarefa" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                        + Adicionar mais uma tarefa
                    </button>
                    <p class="mt-2 text-sm text-gray-500">Cada linha criará uma tarefa separada. O título será extraído do início da descrição ou usará o título base.</p>
                </div>

                <!-- Grid de 2 colunas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Prioridade -->
                    <div>
                        <label for="prioridade" class="block text-sm font-medium text-gray-700">
                            Prioridade *
                        </label>
                        <select name="prioridade" id="prioridade" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="baixa" {{ old('prioridade') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                            <option value="media" {{ old('prioridade', 'media') == 'media' ? 'selected' : '' }}>Média</option>
                            <option value="alta" {{ old('prioridade') == 'alta' ? 'selected' : '' }}>Alta</option>
                            <option value="urgente" {{ old('prioridade') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                        @error('prioridade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Data de Vencimento -->
                    <div id="data-vencimento-unica">
                        <label for="data_vencimento" class="block text-sm font-medium text-gray-700">
                            Data de Vencimento (Opcional)
                        </label>
                        <input type="datetime-local" name="data_vencimento" id="data_vencimento"
                               value="{{ old('data_vencimento') }}"
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('data_vencimento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tarefa Recorrente -->
                <div class="space-y-4" id="secao-recorrente">
                    <div class="flex items-center">
                        <input type="checkbox" name="recorrente" id="recorrente" value="1" 
                               {{ old('recorrente') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="recorrente" class="ml-2 block text-sm text-gray-900">
                            Tarefa recorrente
                        </label>
                    </div>

                    <div id="frequencia-container" class="hidden">
                        <label for="frequencia_recorrencia" class="block text-sm font-medium text-gray-700">
                            Frequência
                        </label>
                        <select name="frequencia_recorrencia" id="frequencia_recorrencia"
                                class="mt-1 block w-full md:w-1/3 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione...</option>
                            <option value="diaria" {{ old('frequencia_recorrencia') == 'diaria' ? 'selected' : '' }}>Diária</option>
                            <option value="semanal" {{ old('frequencia_recorrencia') == 'semanal' ? 'selected' : '' }}>Semanal</option>
                            <option value="mensal" {{ old('frequencia_recorrencia') == 'mensal' ? 'selected' : '' }}>Mensal</option>
                        </select>
                        @error('frequencia_recorrencia')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tarefa de Teste -->
                <div class="space-y-4 border-t pt-4" id="secao-teste">
                    <div class="flex items-center">
                        <input type="checkbox" name="criar_tarefa_teste" id="criar_tarefa_teste" value="1" 
                               {{ old('criar_tarefa_teste') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="criar_tarefa_teste" class="ml-2 block text-sm text-gray-900">
                            Criar tarefa de teste após conclusão
                        </label>
                    </div>

                    <div id="testador-container" class="hidden">
                        <label for="testador_id" class="block text-sm font-medium text-gray-700">
                            Responsável pelos testes
                        </label>
                        <select name="testador_id" id="testador_id"
                                class="mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione um colaborador...</option>
                            @foreach($colaboradores as $colab)
                                <option value="{{ $colab->id }}" {{ old('testador_id') == $colab->id ? 'selected' : '' }}>
                                    {{ $colab->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('testador_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Uma tarefa de teste será criada automaticamente para este colaborador quando a tarefa principal for concluída.</p>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('minhas-tarefas') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Criar Tarefa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Múltiplas tarefas
const multiplasCheckbox = document.getElementById('multiplas_tarefas');
const tituloUnico = document.getElementById('titulo-unico');
const tituloMultiplo = document.getElementById('titulo-multiplo');
const descricaoUnica = document.getElementById('descricao-unica');
const descricoesMultiplas = document.getElementById('descricoes-multiplas');
const dataVencimentoUnica = document.getElementById('data-vencimento-unica');
const secaoRecorrente = document.getElementById('secao-recorrente');
const secaoTeste = document.getElementById('secao-teste');
const tarefasContainer = document.getElementById('tarefas-container');

multiplasCheckbox.addEventListener('change', function() {
    if (this.checked) {
        // Modo múltiplas tarefas
        tituloUnico.classList.add('hidden');
        tituloMultiplo.classList.remove('hidden');
        descricaoUnica.classList.add('hidden');
        descricoesMultiplas.classList.remove('hidden');
        dataVencimentoUnica.classList.add('hidden');
        secaoRecorrente.classList.add('hidden');
        secaoTeste.classList.add('hidden');
        
        // Desabilitar campos únicos
        document.getElementById('titulo').required = false;
        document.getElementById('descricao').required = false;
        
        // Habilitar campos múltiplos
        const primeiraDescricao = document.querySelector('textarea[name="descricoes[]"]');
        if (primeiraDescricao) primeiraDescricao.required = true;
    } else {
        // Modo tarefa única
        tituloUnico.classList.remove('hidden');
        tituloMultiplo.classList.add('hidden');
        descricaoUnica.classList.remove('hidden');
        descricoesMultiplas.classList.add('hidden');
        dataVencimentoUnica.classList.remove('hidden');
        secaoRecorrente.classList.remove('hidden');
        secaoTeste.classList.remove('hidden');
        
        // Habilitar campos únicos
        document.getElementById('titulo').required = true;
    }
});

// Adicionar nova tarefa
document.getElementById('adicionar-tarefa').addEventListener('click', function() {
    const tarefaItem = document.createElement('div');
    tarefaItem.className = 'tarefa-item';
    tarefaItem.innerHTML = `
        <div class="flex gap-2">
            <textarea name="descricoes[]" rows="2"
                      class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Descrição da tarefa..." required></textarea>
            <input type="date" name="prazos[]" 
                   class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Prazo (opcional)">
            <button type="button" class="remover-tarefa px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    tarefasContainer.appendChild(tarefaItem);
    
    // Atualizar visibilidade dos botões de remover
    atualizarBotoesRemover();
});

// Remover tarefa
tarefasContainer.addEventListener('click', function(e) {
    if (e.target.closest('.remover-tarefa')) {
        const tarefaItem = e.target.closest('.tarefa-item');
        tarefaItem.remove();
        atualizarBotoesRemover();
    }
});

function atualizarBotoesRemover() {
    const tarefas = tarefasContainer.querySelectorAll('.tarefa-item');
    tarefas.forEach((tarefa, index) => {
        const botaoRemover = tarefa.querySelector('.remover-tarefa');
        if (tarefas.length > 1) {
            botaoRemover.classList.remove('hidden');
        } else {
            botaoRemover.classList.add('hidden');
        }
    });
}

// Verificar se modo múltiplo está ativo ao carregar
if (multiplasCheckbox.checked) {
    multiplasCheckbox.dispatchEvent(new Event('change'));
}

// Tarefa recorrente
document.getElementById('recorrente').addEventListener('change', function() {
    const frequenciaContainer = document.getElementById('frequencia-container');
    const frequenciaSelect = document.getElementById('frequencia_recorrencia');
    
    if (this.checked) {
        frequenciaContainer.classList.remove('hidden');
        frequenciaSelect.required = true;
    } else {
        frequenciaContainer.classList.add('hidden');
        frequenciaSelect.required = false;
        frequenciaSelect.value = '';
    }
});

// Verificar se já está marcado ao carregar a página
if (document.getElementById('recorrente').checked) {
    document.getElementById('frequencia-container').classList.remove('hidden');
    document.getElementById('frequencia_recorrencia').required = true;
}

// Tarefa de teste
document.getElementById('criar_tarefa_teste').addEventListener('change', function() {
    const testadorContainer = document.getElementById('testador-container');
    const testadorSelect = document.getElementById('testador_id');
    
    if (this.checked) {
        testadorContainer.classList.remove('hidden');
        testadorSelect.required = true;
    } else {
        testadorContainer.classList.add('hidden');
        testadorSelect.required = false;
        testadorSelect.value = '';
    }
});

// Verificar se já está marcado ao carregar a página
if (document.getElementById('criar_tarefa_teste').checked) {
    document.getElementById('testador-container').classList.remove('hidden');
    document.getElementById('testador_id').required = true;
}
</script>
@endsection