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
                <div class="flex gap-2">
                    <button type="button" id="btn-assistente-ia" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        Assistente IA
                    </button>
                    <a href="{{ route('minhas-tarefas') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Voltar
                    </a>
                </div>
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

<!-- Modal do Assistente IA -->
<div id="modal-assistente-ia" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-900">Assistente IA para Tarefas</h3>
                <button type="button" id="fechar-modal-ia" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <p class="text-gray-600 mb-6">Descreva as tarefas por texto ou áudio e deixe a IA organizar para você!</p>
            
            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button type="button" 
                            class="tab-button border-b-2 border-purple-500 py-2 px-1 text-sm font-medium text-purple-600"
                            data-tab="texto">
                        Texto
                    </button>
                    <button type="button" 
                            class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300"
                            data-tab="audio">
                        Áudio
                    </button>
                </nav>
            </div>
            
            <!-- Tab Content -->
            <div id="tab-texto" class="tab-content">
                <textarea id="input-texto-ia" rows="6" 
                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                          placeholder="Exemplo: Preciso criar 3 tarefas para o projeto X:
- Implementar login de usuários com prazo para sexta-feira
- Criar tela de dashboard
- Configurar banco de dados"></textarea>
            </div>
            
            <div id="tab-audio" class="tab-content hidden">
                <div class="text-center py-8">
                    <button type="button" id="btn-gravar-audio" 
                            class="mx-auto bg-red-500 hover:bg-red-600 text-white rounded-full p-6 transition-all transform hover:scale-105">
                        <svg id="icon-microfone" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                        </svg>
                        <svg id="icon-gravando" class="w-8 h-8 hidden animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="8"></circle>
                        </svg>
                    </button>
                    <p id="status-gravacao" class="mt-4 text-gray-600">Clique para gravar</p>
                    <div id="audio-preview" class="mt-4 hidden">
                        <audio id="audio-gravado" controls class="mx-auto"></audio>
                    </div>
                </div>
            </div>
            
            <!-- Exemplos -->
            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Exemplos de como descrever:</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• "Criar 3 tarefas urgentes para João: revisar código, fazer testes, documentar API"</li>
                    <li>• "Tarefas do projeto ABC com prazo para próxima semana: design da home, implementar carrinho, integrar pagamento"</li>
                    <li>• "Setup inicial do projeto: configurar ambiente, criar banco de dados, fazer deploy inicial"</li>
                </ul>
            </div>
            
            <!-- Botões -->
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" id="cancelar-ia" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancelar
                </button>
                <button type="button" id="processar-ia" 
                        class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2">
                    <svg id="loading-ia" class="animate-spin h-5 w-5 hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span id="texto-processar">Processar com IA</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Adicionar ao script existente
(function() {
    // Modal do Assistente IA
    const modalIA = document.getElementById('modal-assistente-ia');
    const btnAssistenteIA = document.getElementById('btn-assistente-ia');
    const fecharModalIA = document.getElementById('fechar-modal-ia');
    const cancelarIA = document.getElementById('cancelar-ia');
    const processarIA = document.getElementById('processar-ia');
    const loadingIA = document.getElementById('loading-ia');
    const textoProcessar = document.getElementById('texto-processar');
    
    // Tabs
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    // Áudio
    const btnGravarAudio = document.getElementById('btn-gravar-audio');
    const iconMicrofone = document.getElementById('icon-microfone');
    const iconGravando = document.getElementById('icon-gravando');
    const statusGravacao = document.getElementById('status-gravacao');
    const audioPreview = document.getElementById('audio-preview');
    const audioGravado = document.getElementById('audio-gravado');
    
    let mediaRecorder;
    let audioChunks = [];
    let audioBlob;
    let isRecording = false;
    let currentTab = 'texto';
    
    // Abrir modal
    btnAssistenteIA.addEventListener('click', () => {
        modalIA.classList.remove('hidden');
    });
    
    // Fechar modal
    [fecharModalIA, cancelarIA].forEach(btn => {
        btn.addEventListener('click', () => {
            modalIA.classList.add('hidden');
            resetModal();
        });
    });
    
    // Fechar ao clicar fora
    modalIA.addEventListener('click', (e) => {
        if (e.target === modalIA) {
            modalIA.classList.add('hidden');
            resetModal();
        }
    });
    
    // Tabs
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tab = button.dataset.tab;
            currentTab = tab;
            
            // Update button styles
            tabButtons.forEach(btn => {
                btn.classList.remove('border-purple-500', 'text-purple-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('border-purple-500', 'text-purple-600');
            
            // Show/hide content
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(`tab-${tab}`).classList.remove('hidden');
        });
    });
    
    // Gravação de áudio
    btnGravarAudio.addEventListener('click', async () => {
        if (!isRecording) {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];
                
                mediaRecorder.addEventListener('dataavailable', event => {
                    audioChunks.push(event.data);
                });
                
                mediaRecorder.addEventListener('stop', () => {
                    audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    const audioUrl = URL.createObjectURL(audioBlob);
                    audioGravado.src = audioUrl;
                    audioPreview.classList.remove('hidden');
                    
                    // Parar todas as tracks
                    stream.getTracks().forEach(track => track.stop());
                });
                
                mediaRecorder.start();
                isRecording = true;
                
                // UI updates
                iconMicrofone.classList.add('hidden');
                iconGravando.classList.remove('hidden');
                statusGravacao.textContent = 'Gravando... Clique para parar';
                btnGravarAudio.classList.remove('bg-red-500', 'hover:bg-red-600');
                btnGravarAudio.classList.add('bg-red-600', 'hover:bg-red-700');
                
            } catch (err) {
                console.error('Erro ao acessar microfone:', err);
                alert('Erro ao acessar o microfone. Verifique as permissões.');
            }
        } else {
            mediaRecorder.stop();
            isRecording = false;
            
            // UI updates
            iconMicrofone.classList.remove('hidden');
            iconGravando.classList.add('hidden');
            statusGravacao.textContent = 'Gravação concluída';
            btnGravarAudio.classList.add('bg-red-500', 'hover:bg-red-600');
            btnGravarAudio.classList.remove('bg-red-600', 'hover:bg-red-700');
        }
    });
    
    // Processar com IA
    processarIA.addEventListener('click', async () => {
        let inputData = {};
        
        if (currentTab === 'texto') {
            const texto = document.getElementById('input-texto-ia').value;
            if (!texto.trim()) {
                alert('Por favor, descreva as tarefas antes de processar.');
                return;
            }
            inputData = { tipo: 'texto', conteudo: texto };
        } else {
            if (!audioBlob) {
                alert('Por favor, grave um áudio antes de processar.');
                return;
            }
            inputData = { tipo: 'audio', conteudo: audioBlob };
        }
        
        // Mostrar loading
        loadingIA.classList.remove('hidden');
        textoProcessar.textContent = 'Processando...';
        processarIA.disabled = true;
        
        try {
            const formData = new FormData();
            formData.append('tipo', inputData.tipo);
            
            if (inputData.tipo === 'texto') {
                formData.append('conteudo', inputData.conteudo);
            } else {
                formData.append('audio', inputData.conteudo, 'audio.wav');
            }
            
            const response = await fetch('/tarefa/processar-ia', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                preencherFormulario(result.tarefas);
                modalIA.classList.add('hidden');
                resetModal();
            } else {
                alert(result.message || 'Erro ao processar com IA');
            }
            
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao processar a solicitação');
        } finally {
            loadingIA.classList.add('hidden');
            textoProcessar.textContent = 'Processar com IA';
            processarIA.disabled = false;
        }
    });
    
    // Preencher formulário com dados da IA
    function preencherFormulario(tarefas) {
        if (tarefas.length > 1) {
            // Ativar modo múltiplas tarefas
            document.getElementById('multiplas_tarefas').checked = true;
            document.getElementById('multiplas_tarefas').dispatchEvent(new Event('change'));
            
            // Limpar tarefas existentes
            const container = document.getElementById('tarefas-container');
            container.innerHTML = '';
            
            // Adicionar primeira tarefa
            const primeiraDiv = document.createElement('div');
            primeiraDiv.className = 'tarefa-item';
            primeiraDiv.innerHTML = `
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
            `;
            container.appendChild(primeiraDiv);
            
            // Adicionar cada tarefa
            tarefas.forEach((tarefa, index) => {
                if (index > 0) {
                    document.getElementById('adicionar-tarefa').click();
                }
                
                const textareas = container.querySelectorAll('textarea[name="descricoes[]"]');
                const prazos = container.querySelectorAll('input[name="prazos[]"]');
                
                if (textareas[index]) {
                    textareas[index].value = tarefa.descricao;
                }
                
                if (prazos[index] && tarefa.prazo) {
                    prazos[index].value = tarefa.prazo;
                }
            });
            
            // Preencher campos comuns
            if (tarefas[0].titulo_base) {
                document.getElementById('titulo_base').value = tarefas[0].titulo_base;
            }
            
        } else if (tarefas.length === 1) {
            // Modo tarefa única
            document.getElementById('multiplas_tarefas').checked = false;
            document.getElementById('multiplas_tarefas').dispatchEvent(new Event('change'));
            
            const tarefa = tarefas[0];
            document.getElementById('titulo').value = tarefa.titulo || '';
            document.getElementById('descricao').value = tarefa.descricao || '';
            
            if (tarefa.prazo) {
                document.getElementById('data_vencimento').value = tarefa.prazo + 'T09:00';
            }
        }
        
        // Preencher outros campos comuns se fornecidos
        if (tarefas[0].projeto_id) {
            document.getElementById('projeto_id').value = tarefas[0].projeto_id;
        }
        
        if (tarefas[0].colaborador_id) {
            document.getElementById('colaborador_id').value = tarefas[0].colaborador_id;
        }
        
        if (tarefas[0].prioridade) {
            document.getElementById('prioridade').value = tarefas[0].prioridade;
        }
        
        // Mostrar notificação de sucesso
        showNotification('Formulário preenchido com sucesso! Revise e ajuste conforme necessário.');
    }
    
    // Reset modal
    function resetModal() {
        document.getElementById('input-texto-ia').value = '';
        audioPreview.classList.add('hidden');
        audioBlob = null;
        statusGravacao.textContent = 'Clique para gravar';
        currentTab = 'texto';
        
        // Reset tabs
        tabButtons[0].click();
    }
    
    // Notificação
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
})();
</script>
@endsection