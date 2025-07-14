@extends('layouts.admin')

@section('title', 'Tarefa: ' . $tarefa->titulo)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">{{ $tarefa->titulo }}</h2>
        <div class="flex space-x-3">
            <!-- Botões de Ação -->
            @if($tarefa->status == 'pendente')
                <form action="{{ route('admin.tarefas.iniciar', $tarefa) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Iniciar Tarefa
                    </button>
                </form>
            @endif

            @if($tarefa->status == 'em_andamento')
                <button type="button" onclick="openConcluirModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Concluir Tarefa
                </button>
            @endif

            @if(!in_array($tarefa->status, ['concluida', 'cancelada']))
                <button type="button" onclick="openCancelarModal()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Cancelar Tarefa
                </button>
            @endif

            <a href="{{ route('admin.tarefas.edit', $tarefa) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Editar Tarefa
            </a>
            <a href="{{ route('admin.tarefas.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informações da Tarefa -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Detalhes da Tarefa</h3>
                
                @if($tarefa->descricao)
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Descrição</h4>
                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $tarefa->descricao }}</p>
                    </div>
                @endif

                @if($tarefa->observacoes)
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Observações</h4>
                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $tarefa->observacoes }}</p>
                    </div>
                @endif

                <!-- Status e Tags -->
                <div class="flex flex-wrap gap-2 mb-6">
                    @php
                        $statusColors = [
                            'pendente' => 'bg-gray-100 text-gray-800',
                            'em_andamento' => 'bg-blue-100 text-blue-800',
                            'concluida' => 'bg-green-100 text-green-800',
                            'cancelada' => 'bg-red-100 text-red-800'
                        ];
                        $prioridadeColors = [
                            'baixa' => 'bg-green-100 text-green-800',
                            'media' => 'bg-yellow-100 text-yellow-800',
                            'alta' => 'bg-orange-100 text-orange-800',
                            'urgente' => 'bg-red-100 text-red-800'
                        ];
                        $tipoColors = [
                            'manual' => 'bg-gray-100 text-gray-800',
                            'automatica_feedback' => 'bg-purple-100 text-purple-800',
                            'automatica_aprovacao' => 'bg-indigo-100 text-indigo-800'
                        ];
                    @endphp
                    
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$tarefa->status] ?? 'bg-gray-100 text-gray-800' }}">
                        Status: {{ ucfirst(str_replace('_', ' ', $tarefa->status)) }}
                    </span>
                    
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $prioridadeColors[$tarefa->prioridade] ?? 'bg-gray-100 text-gray-800' }}">
                        Prioridade: {{ ucfirst($tarefa->prioridade) }}
                    </span>
                    
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $tipoColors[$tarefa->tipo] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ str_replace(['automatica_', '_'], ['Auto ', ' '], ucfirst($tarefa->tipo)) }}
                    </span>

                    @if($tarefa->recorrente)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Recorrente ({{ ucfirst($tarefa->frequencia_recorrencia) }})
                        </span>
                    @endif
                </div>

                <!-- Informações de Tempo -->
                @if($tarefa->data_inicio || $tarefa->data_fim || $tarefa->data_vencimento)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Cronologia</h4>
                        <div class="space-y-2 text-sm">
                            @if($tarefa->data_vencimento)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Vencimento:</span>
                                    <span class="text-gray-900">{{ $tarefa->data_vencimento->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                            @if($tarefa->data_inicio)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Iniciado em:</span>
                                    <span class="text-gray-900">{{ $tarefa->data_inicio->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                            @if($tarefa->data_fim)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Finalizado em:</span>
                                    <span class="text-gray-900">{{ $tarefa->data_fim->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                            @if($tarefa->duracao)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Duração total:</span>
                                    <span class="text-gray-900">{{ $tarefa->duracao }} minutos</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar com informações relacionadas -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informações</h3>
                
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Colaborador</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="{{ route('admin.colaboradores.show', $tarefa->colaborador) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $tarefa->colaborador->nome }}
                            </a>
                        </dd>
                    </div>
                    
                    @if($tarefa->projeto)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Projeto</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('admin.projetos.show', $tarefa->projeto) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $tarefa->projeto->nome }}
                                </a>
                            </dd>
                        </div>
                    @endif
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tarefa->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Atualizado em</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tarefa->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        @if($tarefa->projeto && $tarefa->projeto->cliente)
            <!-- Informações do Cliente -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Cliente do Projeto</h3>
                    
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nome</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('admin.clientes.show', $tarefa->projeto->cliente) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $tarefa->projeto->cliente->nome }}
                                </a>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $tarefa->projeto->cliente->email }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Concluir Tarefa -->
<div id="concluirModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Concluir Tarefa</h3>
            <form action="{{ route('admin.tarefas.concluir', $tarefa) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="observacoes_concluir" class="block text-sm font-medium text-gray-700">Observações (opcional)</label>
                    <textarea name="observacoes" id="observacoes_concluir" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeConcluirModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Concluir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cancelar Tarefa -->
<div id="cancelarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cancelar Tarefa</h3>
            <form action="{{ route('admin.tarefas.cancelar', $tarefa) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="observacoes_cancelar" class="block text-sm font-medium text-gray-700">Motivo do cancelamento *</label>
                    <textarea name="observacoes" id="observacoes_cancelar" rows="3" required
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCancelarModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Voltar
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Cancelar Tarefa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openConcluirModal() {
    document.getElementById('concluirModal').classList.remove('hidden');
}

function closeConcluirModal() {
    document.getElementById('concluirModal').classList.add('hidden');
    document.getElementById('observacoes_concluir').value = '';
}

function openCancelarModal() {
    document.getElementById('cancelarModal').classList.remove('hidden');
}

function closeCancelarModal() {
    document.getElementById('cancelarModal').classList.add('hidden');
    document.getElementById('observacoes_cancelar').value = '';
}
</script>
@endsection