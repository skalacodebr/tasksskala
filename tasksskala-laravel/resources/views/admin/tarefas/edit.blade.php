@extends('layouts.admin')

@section('title', 'Editar Tarefa')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Editar Tarefa</h3>
            
            <form action="{{ route('admin.tarefas.update', $tarefa) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Título -->
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
                        <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $tarefa->titulo) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('titulo') border-red-500 @enderror">
                        @error('titulo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea name="descricao" id="descricao" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('descricao') border-red-500 @enderror">{{ old('descricao', $tarefa->descricao) }}</textarea>
                        @error('descricao')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Colaborador -->
                    <div>
                        <label for="colaborador_id" class="block text-sm font-medium text-gray-700">Colaborador</label>
                        <select name="colaborador_id" id="colaborador_id" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('colaborador_id') border-red-500 @enderror">
                            <option value="">Selecione um colaborador</option>
                            @foreach($colaboradores as $colaborador)
                                <option value="{{ $colaborador->id }}" {{ old('colaborador_id', $tarefa->colaborador_id) == $colaborador->id ? 'selected' : '' }}>
                                    {{ $colaborador->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('colaborador_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Projeto -->
                    <div>
                        <label for="projeto_id" class="block text-sm font-medium text-gray-700">Projeto (opcional)</label>
                        <select name="projeto_id" id="projeto_id" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('projeto_id') border-red-500 @enderror">
                            <option value="">Nenhum projeto específico</option>
                            @foreach($projetos as $projeto)
                                <option value="{{ $projeto->id }}" {{ old('projeto_id', $tarefa->projeto_id) == $projeto->id ? 'selected' : '' }}>
                                    {{ $projeto->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('projeto_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo</label>
                        <select name="tipo" id="tipo" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('tipo') border-red-500 @enderror">
                            <option value="manual" {{ old('tipo', $tarefa->tipo) == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="automatica_feedback" {{ old('tipo', $tarefa->tipo) == 'automatica_feedback' ? 'selected' : '' }}>Feedback Automático</option>
                            <option value="automatica_aprovacao" {{ old('tipo', $tarefa->tipo) == 'automatica_aprovacao' ? 'selected' : '' }}>Aprovação Automática</option>
                        </select>
                        @error('tipo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prioridade -->
                    <div>
                        <label for="prioridade" class="block text-sm font-medium text-gray-700">Prioridade</label>
                        <select name="prioridade" id="prioridade" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('prioridade') border-red-500 @enderror">
                            <option value="baixa" {{ old('prioridade', $tarefa->prioridade) == 'baixa' ? 'selected' : '' }}>Baixa</option>
                            <option value="media" {{ old('prioridade', $tarefa->prioridade) == 'media' ? 'selected' : '' }}>Média</option>
                            <option value="alta" {{ old('prioridade', $tarefa->prioridade) == 'alta' ? 'selected' : '' }}>Alta</option>
                            <option value="urgente" {{ old('prioridade', $tarefa->prioridade) == 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                        @error('prioridade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                            <option value="pendente" {{ old('status', $tarefa->status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="em_andamento" {{ old('status', $tarefa->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="concluida" {{ old('status', $tarefa->status) == 'concluida' ? 'selected' : '' }}>Concluída</option>
                            <option value="cancelada" {{ old('status', $tarefa->status) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Data de Vencimento -->
                    <div>
                        <label for="data_vencimento" class="block text-sm font-medium text-gray-700">Data de Vencimento (opcional)</label>
                        <input type="datetime-local" name="data_vencimento" id="data_vencimento" 
                               value="{{ old('data_vencimento', $tarefa->data_vencimento ? $tarefa->data_vencimento->format('Y-m-d\TH:i') : '') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('data_vencimento') border-red-500 @enderror">
                        @error('data_vencimento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Observações -->
                    <div>
                        <label for="observacoes" class="block text-sm font-medium text-gray-700">Observações</label>
                        <textarea name="observacoes" id="observacoes" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('observacoes') border-red-500 @enderror">{{ old('observacoes', $tarefa->observacoes) }}</textarea>
                        @error('observacoes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Recorrente -->
                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" name="recorrente" id="recorrente" value="1" {{ old('recorrente', $tarefa->recorrente) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="recorrente" class="ml-2 text-sm text-gray-700">
                                Tarefa recorrente
                            </label>
                        </div>
                    </div>

                    <!-- Frequência de Recorrência -->
                    <div id="frequencia_div" style="display: {{ old('recorrente', $tarefa->recorrente) ? 'block' : 'none' }};">
                        <label for="frequencia_recorrencia" class="block text-sm font-medium text-gray-700">Frequência</label>
                        <select name="frequencia_recorrencia" id="frequencia_recorrencia" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione a frequência</option>
                            <option value="diaria" {{ old('frequencia_recorrencia', $tarefa->frequencia_recorrencia) == 'diaria' ? 'selected' : '' }}>Diária</option>
                            <option value="semanal" {{ old('frequencia_recorrencia', $tarefa->frequencia_recorrencia) == 'semanal' ? 'selected' : '' }}>Semanal</option>
                            <option value="mensal" {{ old('frequencia_recorrencia', $tarefa->frequencia_recorrencia) == 'mensal' ? 'selected' : '' }}>Mensal</option>
                        </select>
                        @error('frequencia_recorrencia')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informações de Tempo -->
                    @if($tarefa->data_inicio || $tarefa->data_fim)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Informações de Tempo</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            @if($tarefa->data_inicio)
                                <div>
                                    <span class="font-medium text-gray-700">Iniciado em:</span>
                                    <span class="text-gray-900">{{ $tarefa->data_inicio->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                            @if($tarefa->data_fim)
                                <div>
                                    <span class="font-medium text-gray-700">Finalizado em:</span>
                                    <span class="text-gray-900">{{ $tarefa->data_fim->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                            @if($tarefa->duracao)
                                <div>
                                    <span class="font-medium text-gray-700">Duração:</span>
                                    <span class="text-gray-900">{{ $tarefa->duracao }} minutos</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.tarefas.index') }}" 
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700">
                        Atualizar Tarefa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const recorrenteCheckbox = document.getElementById('recorrente');
    const frequenciaDiv = document.getElementById('frequencia_div');

    function toggleFrequencia() {
        if (recorrenteCheckbox.checked) {
            frequenciaDiv.style.display = 'block';
        } else {
            frequenciaDiv.style.display = 'none';
            document.getElementById('frequencia_recorrencia').value = '';
        }
    }

    recorrenteCheckbox.addEventListener('change', toggleFrequencia);
});
</script>
@endsection