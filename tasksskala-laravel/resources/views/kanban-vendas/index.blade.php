@extends('layouts.colaborador')

@section('title', 'Kanban de Vendas')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white mb-2">Kanban de Vendas</h1>
        <p class="text-gray-400">Gerencie o pipeline de vendas da empresa</p>
    </div>

    <!-- Botões de Ação -->
    <div class="mb-6 flex justify-between items-center">
        <button onclick="abrirModalNovaColuna()" class="btn-primary-dark px-4 py-2 rounded">
            Nova Coluna
        </button>
        <button onclick="abrirModalNovoCard()" class="btn-secondary-dark px-4 py-2 rounded">
            Novo Card
        </button>
    </div>

    <!-- Kanban Board -->
    <div class="overflow-x-auto pb-4">
        <div id="kanban-board" class="flex gap-4 min-w-max">
            @foreach($colunas as $coluna)
            <div class="kanban-coluna flex-shrink-0 w-80" data-coluna-id="{{ $coluna->id }}">
                <div class="card-dark rounded-lg p-4">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $coluna->cor }}"></div>
                            <h3 class="font-semibold text-white">{{ $coluna->nome }}</h3>
                            <span class="text-sm text-gray-400">({{ $coluna->cards->count() }})</span>
                        </div>
                        <div class="flex gap-1">
                            <button onclick="editarColuna({{ $coluna->id }}, '{{ $coluna->nome }}', '{{ $coluna->cor }}', '{{ $coluna->descricao }}')" 
                                    class="text-gray-400 hover:text-white p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            @if($coluna->cards->count() == 0)
                            <button onclick="excluirColuna({{ $coluna->id }})" 
                                    class="text-gray-400 hover:text-red-400 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="kanban-cards space-y-3" data-coluna-id="{{ $coluna->id }}">
                        @foreach($coluna->cards as $card)
                        <div class="kanban-card card-dark p-3 rounded cursor-move hover:border-gray-600 transition-colors" 
                             data-card-id="{{ $card->id }}" 
                             draggable="true">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-medium text-white text-sm">{{ $card->titulo }}</h4>
                                <div class="flex gap-1">
                                    <button onclick="editarCard({{ $card->id }})" 
                                            class="text-gray-400 hover:text-white p-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            @if($card->cliente)
                            <p class="text-xs text-gray-400 mb-1">Cliente: {{ $card->cliente->nome }}</p>
                            @endif
                            
                            @if($card->colaborador)
                            <p class="text-xs text-gray-400 mb-1">Responsável: {{ $card->colaborador->nome }}</p>
                            @endif
                            
                            @if($card->valor)
                            <p class="text-xs text-green-400 mb-1">R$ {{ number_format($card->valor, 2, ',', '.') }}</p>
                            @endif
                            
                            @if($card->data_previsao)
                            <p class="text-xs text-gray-400">Previsão: {{ $card->data_previsao->format('d/m/Y') }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal Nova Coluna -->
<div id="modalNovaColuna" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold text-white mb-4">Nova Coluna</h2>
        <form id="formNovaColuna">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Nome</label>
                <input type="text" name="nome" required class="input-dark w-full px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Descrição</label>
                <textarea name="descricao" rows="2" class="input-dark w-full px-3 py-2 rounded"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Cor</label>
                <input type="color" name="cor" value="#007bff" class="h-10 w-full rounded cursor-pointer">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="fecharModalNovaColuna()" class="btn-secondary-dark px-4 py-2 rounded">
                    Cancelar
                </button>
                <button type="submit" class="btn-primary-dark px-4 py-2 rounded">
                    Criar Coluna
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Coluna -->
<div id="modalEditarColuna" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold text-white mb-4">Editar Coluna</h2>
        <form id="formEditarColuna">
            <input type="hidden" name="coluna_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Nome</label>
                <input type="text" name="nome" required class="input-dark w-full px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Descrição</label>
                <textarea name="descricao" rows="2" class="input-dark w-full px-3 py-2 rounded"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Cor</label>
                <input type="color" name="cor" class="h-10 w-full rounded cursor-pointer">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="fecharModalEditarColuna()" class="btn-secondary-dark px-4 py-2 rounded">
                    Cancelar
                </button>
                <button type="submit" class="btn-primary-dark px-4 py-2 rounded">
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Novo Card -->
<div id="modalNovoCard" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold text-white mb-4">Novo Card</h2>
        <form id="formNovoCard">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Título</label>
                    <input type="text" name="titulo" required class="input-dark w-full px-3 py-2 rounded">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Coluna</label>
                    <select name="kanban_venda_id" required class="input-dark w-full px-3 py-2 rounded">
                        @foreach($colunas as $coluna)
                        <option value="{{ $coluna->id }}">{{ $coluna->nome }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Cliente</label>
                    <select name="cliente_id" class="input-dark w-full px-3 py-2 rounded">
                        <option value="">Selecione...</option>
                        @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Responsável</label>
                    <select name="colaborador_id" class="input-dark w-full px-3 py-2 rounded">
                        <option value="">Selecione...</option>
                        @foreach($colaboradores as $colaborador)
                        <option value="{{ $colaborador->id }}">{{ $colaborador->nome }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Valor</label>
                    <input type="number" name="valor" step="0.01" min="0" class="input-dark w-full px-3 py-2 rounded">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Data Previsão</label>
                    <input type="date" name="data_previsao" class="input-dark w-full px-3 py-2 rounded">
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Descrição</label>
                    <textarea name="descricao" rows="3" class="input-dark w-full px-3 py-2 rounded"></textarea>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Observações</label>
                    <textarea name="observacoes" rows="2" class="input-dark w-full px-3 py-2 rounded"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="fecharModalNovoCard()" class="btn-secondary-dark px-4 py-2 rounded">
                    Cancelar
                </button>
                <button type="submit" class="btn-primary-dark px-4 py-2 rounded">
                    Criar Card
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Card -->
<div id="modalEditarCard" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold text-white mb-4">Editar Card</h2>
        <form id="formEditarCard">
            <input type="hidden" name="card_id">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Título</label>
                    <input type="text" name="titulo" required class="input-dark w-full px-3 py-2 rounded">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Cliente</label>
                    <select name="cliente_id" class="input-dark w-full px-3 py-2 rounded">
                        <option value="">Selecione...</option>
                        @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Responsável</label>
                    <select name="colaborador_id" class="input-dark w-full px-3 py-2 rounded">
                        <option value="">Selecione...</option>
                        @foreach($colaboradores as $colaborador)
                        <option value="{{ $colaborador->id }}">{{ $colaborador->nome }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Valor</label>
                    <input type="number" name="valor" step="0.01" min="0" class="input-dark w-full px-3 py-2 rounded">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Data Previsão</label>
                    <input type="date" name="data_previsao" class="input-dark w-full px-3 py-2 rounded">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Data Conclusão</label>
                    <input type="date" name="data_conclusao" class="input-dark w-full px-3 py-2 rounded">
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Descrição</label>
                    <textarea name="descricao" rows="3" class="input-dark w-full px-3 py-2 rounded"></textarea>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Observações</label>
                    <textarea name="observacoes" rows="2" class="input-dark w-full px-3 py-2 rounded"></textarea>
                </div>
            </div>
            
            <div class="flex justify-between items-center mt-6">
                <button type="button" onclick="excluirCard()" class="text-red-400 hover:text-red-300">
                    Excluir Card
                </button>
                <div class="flex gap-3">
                    <button type="button" onclick="fecharModalEditarCard()" class="btn-secondary-dark px-4 py-2 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary-dark px-4 py-2 rounded">
                        Salvar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Variáveis globais
let draggedCard = null;
let cardEditandoId = null;

// Funções de Modal
function abrirModalNovaColuna() {
    document.getElementById('modalNovaColuna').classList.remove('hidden');
    document.getElementById('modalNovaColuna').classList.add('flex');
}

function fecharModalNovaColuna() {
    document.getElementById('modalNovaColuna').classList.add('hidden');
    document.getElementById('modalNovaColuna').classList.remove('flex');
    document.getElementById('formNovaColuna').reset();
}

function abrirModalNovoCard() {
    document.getElementById('modalNovoCard').classList.remove('hidden');
    document.getElementById('modalNovoCard').classList.add('flex');
}

function fecharModalNovoCard() {
    document.getElementById('modalNovoCard').classList.add('hidden');
    document.getElementById('modalNovoCard').classList.remove('flex');
    document.getElementById('formNovoCard').reset();
}

function fecharModalEditarColuna() {
    document.getElementById('modalEditarColuna').classList.add('hidden');
    document.getElementById('modalEditarColuna').classList.remove('flex');
}

function fecharModalEditarCard() {
    document.getElementById('modalEditarCard').classList.add('hidden');
    document.getElementById('modalEditarCard').classList.remove('flex');
    cardEditandoId = null;
}

// Funções de Coluna
function editarColuna(id, nome, cor, descricao) {
    const form = document.getElementById('formEditarColuna');
    form.coluna_id.value = id;
    form.nome.value = nome;
    form.cor.value = cor;
    form.descricao.value = descricao || '';
    
    document.getElementById('modalEditarColuna').classList.remove('hidden');
    document.getElementById('modalEditarColuna').classList.add('flex');
}

async function excluirColuna(id) {
    if (!confirm('Tem certeza que deseja excluir esta coluna?')) return;
    
    try {
        const response = await fetch(`/kanban-vendas/colunas/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            location.reload();
        } else {
            alert(data.error || 'Erro ao excluir coluna');
        }
    } catch (error) {
        alert('Erro ao excluir coluna');
    }
}

// Funções de Card
async function editarCard(id) {
    try {
        const response = await fetch(`/kanban-vendas/cards/${id}`);
        const card = await response.json();
        
        const form = document.getElementById('formEditarCard');
        form.card_id.value = card.id;
        form.titulo.value = card.titulo;
        form.descricao.value = card.descricao || '';
        form.cliente_id.value = card.cliente_id || '';
        form.colaborador_id.value = card.colaborador_id || '';
        form.valor.value = card.valor || '';
        form.data_previsao.value = card.data_previsao || '';
        form.data_conclusao.value = card.data_conclusao || '';
        form.observacoes.value = card.observacoes || '';
        
        cardEditandoId = id;
        
        document.getElementById('modalEditarCard').classList.remove('hidden');
        document.getElementById('modalEditarCard').classList.add('flex');
    } catch (error) {
        alert('Erro ao carregar dados do card');
    }
}

async function excluirCard() {
    if (!cardEditandoId || !confirm('Tem certeza que deseja excluir este card?')) return;
    
    try {
        const response = await fetch(`/kanban-vendas/cards/${cardEditandoId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            location.reload();
        } else {
            alert('Erro ao excluir card');
        }
    } catch (error) {
        alert('Erro ao excluir card');
    }
}

// Formulários
document.getElementById('formNovaColuna').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('/kanban-vendas/colunas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            location.reload();
        } else {
            const error = await response.json();
            alert(error.error || 'Erro ao criar coluna');
        }
    } catch (error) {
        alert('Erro ao criar coluna');
    }
});

document.getElementById('formEditarColuna').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    const colunaId = data.coluna_id;
    delete data.coluna_id;
    
    try {
        const response = await fetch(`/kanban-vendas/colunas/${colunaId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            location.reload();
        } else {
            const error = await response.json();
            alert(error.error || 'Erro ao atualizar coluna');
        }
    } catch (error) {
        alert('Erro ao atualizar coluna');
    }
});

document.getElementById('formNovoCard').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('/kanban-vendas/cards', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            location.reload();
        } else {
            const error = await response.json();
            alert(error.error || 'Erro ao criar card');
        }
    } catch (error) {
        alert('Erro ao criar card');
    }
});

document.getElementById('formEditarCard').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    const cardId = data.card_id;
    delete data.card_id;
    
    try {
        const response = await fetch(`/kanban-vendas/cards/${cardId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            location.reload();
        } else {
            const error = await response.json();
            alert(error.error || 'Erro ao atualizar card');
        }
    } catch (error) {
        alert('Erro ao atualizar card');
    }
});

// Drag and Drop
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.kanban-card');
    const dropZones = document.querySelectorAll('.kanban-cards');
    
    cards.forEach(card => {
        card.addEventListener('dragstart', handleDragStart);
        card.addEventListener('dragend', handleDragEnd);
    });
    
    dropZones.forEach(zone => {
        zone.addEventListener('dragover', handleDragOver);
        zone.addEventListener('drop', handleDrop);
        zone.addEventListener('dragleave', handleDragLeave);
    });
});

function handleDragStart(e) {
    draggedCard = e.target;
    e.target.classList.add('opacity-50');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', e.target.innerHTML);
}

function handleDragEnd(e) {
    e.target.classList.remove('opacity-50');
    
    const dropZones = document.querySelectorAll('.kanban-cards');
    dropZones.forEach(zone => {
        zone.classList.remove('bg-gray-700');
    });
}

function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    
    e.dataTransfer.dropEffect = 'move';
    
    const dropZone = e.currentTarget;
    dropZone.classList.add('bg-gray-700');
    
    const afterElement = getDragAfterElement(dropZone, e.clientY);
    if (afterElement == null) {
        dropZone.appendChild(draggedCard);
    } else {
        dropZone.insertBefore(draggedCard, afterElement);
    }
    
    return false;
}

function handleDragLeave(e) {
    if (e.currentTarget.classList.contains('kanban-cards')) {
        e.currentTarget.classList.remove('bg-gray-700');
    }
}

async function handleDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    }
    
    const dropZone = e.currentTarget;
    const colunaId = dropZone.dataset.colunaId;
    const cardId = draggedCard.dataset.cardId;
    
    const cards = [...dropZone.querySelectorAll('.kanban-card:not(.dragging)')];
    const cardIndex = cards.indexOf(draggedCard);
    
    try {
        const response = await fetch(`/kanban-vendas/cards/${cardId}/mover`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                kanban_venda_id: colunaId,
                nova_ordem: cardIndex
            })
        });
        
        if (!response.ok) {
            location.reload();
        }
    } catch (error) {
        location.reload();
    }
    
    return false;
}

function getDragAfterElement(container, y) {
    const draggableElements = [...container.querySelectorAll('.kanban-card:not(.dragging)')];
    
    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child };
        } else {
            return closest;
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}
</script>

<style>
.kanban-card.dragging {
    opacity: 0.5;
}

.kanban-cards.drag-over {
    background-color: rgba(55, 65, 81, 0.5);
}
</style>
@endsection