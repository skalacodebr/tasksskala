@extends('layouts.colaborador')

@section('title', 'Detalhes da Tarefa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="card-dark shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-primary-dark">{{ $tarefa->titulo }}</h1>
                    
                    <!-- Status e Tags -->
                    <div class="flex flex-wrap gap-2 mt-3">
                        @php
                            $statusColors = [
                                'pendente' => 'bg-gray-800 text-primary-dark',
                                'em_andamento' => 'bg-blue-800 text-blue-200',
                                'concluida' => 'bg-green-800 text-green-200',
                                'cancelada' => 'bg-red-800 text-red-200'
                            ];
                            $prioridadeColors = [
                                'baixa' => 'bg-green-800 text-green-200',
                                'media' => 'bg-yellow-800 text-yellow-200',
                                'alta' => 'bg-orange-800 text-orange-200',
                                'urgente' => 'bg-red-800 text-red-200'
                            ];
                        @endphp
                        
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$tarefa->status] ?? 'bg-gray-800 text-primary-dark' }}">
                            {{ ucfirst(str_replace('_', ' ', $tarefa->status)) }}
                        </span>
                        
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $prioridadeColors[$tarefa->prioridade] ?? 'bg-gray-800 text-primary-dark' }}">
                            Prioridade: {{ ucfirst($tarefa->prioridade) }}
                        </span>

                        @if($tarefa->recorrente)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-800 text-blue-200">
                                Recorrente ({{ ucfirst($tarefa->frequencia_recorrencia) }})
                            </span>
                        @endif
                        
                        @if($tarefa->pausada)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-800 text-yellow-200">
                                Pausada
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    @if($tarefa->status == 'pendente')
                        <form action="{{ route('tarefa.iniciar', $tarefa) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-primary-dark font-bold py-2 px-4 rounded">
                                Iniciar Tarefa
                            </button>
                        </form>
                    @endif

                    @if($tarefa->status == 'em_andamento')
                        @if($tarefa->pausada)
                            <form action="{{ route('tarefa.continuar', $tarefa) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                    Continuar Tarefa
                                </button>
                            </form>
                        @else
                            <form action="{{ route('tarefa.pausar', $tarefa) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                    Pausar Tarefa
                                </button>
                            </form>
                        @endif
                        <button type="button" onclick="openConcluirModal()" class="btn-primary-dark font-bold py-2 px-4 rounded">
                            Concluir Tarefa
                        </button>
                    @endif

                    @if(in_array($tarefa->status, ['pendente', 'em_andamento']))
                        <button type="button" onclick="openTransferirModal()" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                            Transferir
                        </button>
                    @endif

                    <a href="{{ route('minhas-tarefas') }}" class="btn-secondary-dark font-bold py-2 px-4 rounded">
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Detalhes da Tarefa -->
        <div class="lg:col-span-2">
            <div class="card-dark shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">Descrição</h3>
                    
                    @if($tarefa->descricao)
                        <div class="prose max-w-none">
                            <p class="text-muted-dark whitespace-pre-line">{{ $tarefa->descricao }}</p>
                        </div>
                    @else
                        <p class="text-muted-dark italic">Nenhuma descrição fornecida.</p>
                    @endif

                    @if($tarefa->observacoes)
                        <div class="mt-6 pt-6 border-t border-gray-700">
                            <h4 class="text-md leading-6 font-medium text-primary-dark mb-2">Observações</h4>
                            <p class="text-muted-dark whitespace-pre-line">{{ $tarefa->observacoes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Notas -->
            <div class="card-dark shadow rounded-lg mt-6">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-primary-dark">Notas</h3>
                        <button type="button" onclick="openNotaModal()" class="btn-primary-dark text-sm px-3 py-1 rounded">
                            Adicionar Nota
                        </button>
                    </div>
                    
                    @if($tarefa->notas)
                        <div class="bg-gray-800 rounded-lg p-4">
                            <div class="whitespace-pre-line text-muted-dark">{{ $tarefa->notas }}</div>
                        </div>
                    @else
                        <p class="text-muted-dark italic">Nenhuma nota adicionada ainda.</p>
                    @endif
                </div>
            </div>

            <!-- Cronologia -->
            @if($tarefa->data_inicio || $tarefa->data_fim || $tarefa->data_vencimento)
                <div class="card-dark shadow rounded-lg mt-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">Cronologia</h3>
                        
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @if($tarefa->created_at)
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-muted-dark">Tarefa criada</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-muted-dark">
                                                        {{ $tarefa->created_at->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif

                                @if($tarefa->data_inicio)
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-900 bg-opacity-200 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-muted-dark">Tarefa iniciada</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-muted-dark">
                                                        {{ $tarefa->data_inicio->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif

                                @if($tarefa->data_transferencia)
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-orange-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-muted-dark">Tarefa transferida</p>
                                                        <p class="text-xs text-gray-400">De: {{ $tarefa->transferidoDe->nome ?? 'N/A' }}</p>
                                                        @if($tarefa->motivo_transferencia)
                                                            <p class="text-xs text-gray-400 mt-1">Motivo: {{ $tarefa->motivo_transferencia }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-muted-dark">
                                                        {{ $tarefa->data_transferencia->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif

                                @if($tarefa->data_fim)
                                    <li>
                                        <div class="relative">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-green-900 bg-opacity-200 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-muted-dark">Tarefa concluída</p>
                                                        @if($tarefa->duracao)
                                                            <p class="text-xs text-gray-400">Duração: {{ $tarefa->duracao_formatada }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-muted-dark">
                                                        {{ $tarefa->data_fim->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Informações Gerais -->
            <div class="card-dark shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">Informações</h3>
                    
                    <dl class="space-y-4">
                        @if($tarefa->projeto)
                            <div>
                                <dt class="text-sm font-medium text-muted-dark">Projeto</dt>
                                <dd class="mt-1 text-sm text-primary-dark">{{ $tarefa->projeto->nome }}</dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Tipo</dt>
                            <dd class="mt-1 text-sm text-primary-dark">
                                {{ str_replace(['automatica_', '_'], ['Automática ', ' '], ucfirst($tarefa->tipo)) }}
                            </dd>
                        </div>

                        @if($tarefa->data_vencimento)
                            <div>
                                <dt class="text-sm font-medium text-muted-dark">Vencimento</dt>
                                <dd class="mt-1 text-sm text-primary-dark">{{ $tarefa->data_vencimento->format('d/m/Y H:i') }}</dd>
                                <dd class="text-xs text-muted-dark">{{ $tarefa->data_vencimento->diffForHumans() }}</dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Criado em</dt>
                            <dd class="mt-1 text-sm text-primary-dark">{{ $tarefa->created_at->format('d/m/Y H:i') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-muted-dark">Atualizado em</dt>
                            <dd class="mt-1 text-sm text-primary-dark">{{ $tarefa->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        
                        @if($tarefa->tempo_pausado > 0)
                            <div>
                                <dt class="text-sm font-medium text-muted-dark">Tempo pausado</dt>
                                <dd class="mt-1 text-sm text-primary-dark">{{ gmdate('H:i:s', $tarefa->tempo_pausado) }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            @if($tarefa->projeto && $tarefa->projeto->cliente)
                <!-- Informações do Cliente -->
                <div class="card-dark shadow rounded-lg mt-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">Cliente</h3>
                        
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-muted-dark">Nome</dt>
                                <dd class="mt-1 text-sm text-primary-dark">{{ $tarefa->projeto->cliente->nome }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-muted-dark">Email</dt>
                                <dd class="mt-1 text-sm text-primary-dark">{{ $tarefa->projeto->cliente->email }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Concluir Tarefa -->
<div id="concluirModal" class="fixed inset-0 bg-black bg-opacity-80 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md card-dark">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-primary-dark mb-4">Concluir Tarefa</h3>
            <form action="{{ route('tarefa.concluir', $tarefa) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="observacoes_concluir" class="block text-sm font-medium text-muted-dark">Observações (opcional)</label>
                    <textarea name="observacoes" id="observacoes_concluir" rows="3" 
                              class="mt-1 block w-full input-dark rounded-md shadow-sm"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeConcluirModal()" class="btn-secondary-dark font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary-dark font-bold py-2 px-4 rounded">
                        Concluir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Adicionar Nota -->
<div id="notaModal" class="fixed inset-0 bg-black bg-opacity-80 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md card-dark">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-primary-dark mb-4">Adicionar Nota</h3>
            <form action="{{ route('tarefa.nota', $tarefa) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="nota" class="block text-sm font-medium text-muted-dark">Nota</label>
                    <textarea name="nota" id="nota" rows="3" required
                              class="mt-1 block w-full input-dark rounded-md shadow-sm"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeNotaModal()" class="btn-secondary-dark font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary-dark font-bold py-2 px-4 rounded">
                        Adicionar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Transferir Tarefa -->
<div id="transferirModal" class="fixed inset-0 bg-black bg-opacity-80 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md card-dark">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-primary-dark mb-4">Transferir Tarefa</h3>
            <form action="{{ route('tarefa.transferir', $tarefa) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="colaborador_id" class="block text-sm font-medium text-muted-dark">Transferir para</label>
                    <select name="colaborador_id" id="colaborador_id" required
                            class="mt-1 block w-full border-gray-600 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Selecione um colaborador</option>
                        @foreach(App\Models\Colaborador::where('id', '!=', $tarefa->colaborador_id)->orderBy('nome')->get() as $colaborador)
                            <option value="{{ $colaborador->id }}">{{ $colaborador->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="motivo" class="block text-sm font-medium text-muted-dark">Motivo da transferência</label>
                    <textarea name="motivo" id="motivo" rows="3" required
                              class="mt-1 block w-full input-dark rounded-md shadow-sm"
                              placeholder="Explique o motivo da transferência..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeTransferirModal()" class="btn-secondary-dark font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                        Transferir
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

function openNotaModal() {
    document.getElementById('notaModal').classList.remove('hidden');
}

function closeNotaModal() {
    document.getElementById('notaModal').classList.add('hidden');
    document.getElementById('nota').value = '';
}

function openTransferirModal() {
    document.getElementById('transferirModal').classList.remove('hidden');
}

function closeTransferirModal() {
    document.getElementById('transferirModal').classList.add('hidden');
    document.getElementById('colaborador_id').value = '';
    document.getElementById('motivo').value = '';
}
</script>
@endsection