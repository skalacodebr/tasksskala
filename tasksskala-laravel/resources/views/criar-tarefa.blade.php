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

                <!-- Título -->
                <div>
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

                <!-- Descrição -->
                <div>
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
                    <div>
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
                <div class="space-y-4">
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
</script>
@endsection