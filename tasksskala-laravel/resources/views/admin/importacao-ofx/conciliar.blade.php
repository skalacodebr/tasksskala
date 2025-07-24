@extends('layouts.admin')

@section('title', 'Conciliar Transações OFX')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Transações Pendentes de Conciliação</h5>
                        <div>
                            <a href="{{ route('admin.importacao-ofx.create') }}" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Nova Importação
                            </a>
                            <a href="{{ route('admin.importacao-ofx.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list"></i> Todas as Transações
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if($transacoesPendentes->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Não há transações pendentes de conciliação.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Descrição</th>
                                        <th>Beneficiário</th>
                                        <th>Valor</th>
                                        <th>Tipo</th>
                                        <th>Conta Bancária</th>
                                        <th width="200">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transacoesPendentes as $transacao)
                                    <tr id="transacao-{{ $transacao->id }}">
                                        <td>{{ $transacao->data_transacao->format('d/m/Y') }}</td>
                                        <td>{{ $transacao->descricao }}</td>
                                        <td>{{ $transacao->beneficiario }}</td>
                                        <td>
                                            <span class="badge bg-{{ $transacao->tipo_conta == 'pagar' ? 'danger' : 'success' }}">
                                                R$ {{ number_format($transacao->valor, 2, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $transacao->tipo_conta == 'pagar' ? 'warning' : 'info' }}">
                                                {{ $transacao->tipo_conta == 'pagar' ? 'A Pagar' : 'A Receber' }}
                                            </span>
                                        </td>
                                        <td>{{ $transacao->conta_bancaria }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" 
                                                    onclick="abrirModalConciliacao({{ $transacao->id }})">
                                                <i class="fas fa-link"></i> Conciliar
                                            </button>
                                            <button class="btn btn-sm btn-secondary" 
                                                    onclick="ignorarTransacao({{ $transacao->id }})">
                                                <i class="fas fa-eye-slash"></i> Ignorar
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $transacoesPendentes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Conciliação -->
<div class="modal fade" id="modalConciliacao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Conciliar Transação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detalhesTransacao" class="mb-4">
                    <!-- Detalhes da transação serão carregados aqui -->
                </div>

                <ul class="nav nav-tabs" id="tabsConciliacao" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="vincular-tab" data-bs-toggle="tab" 
                                data-bs-target="#vincular" type="button" role="tab">
                            <i class="fas fa-link"></i> Vincular a Conta Existente
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="criar-tab" data-bs-toggle="tab" 
                                data-bs-target="#criar" type="button" role="tab">
                            <i class="fas fa-plus"></i> Criar Nova Conta
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="conteudoTabsConciliacao">
                    <div class="tab-pane fade show active" id="vincular" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label">Contas Sugeridas</label>
                            <div id="contasSugeridas" class="list-group mb-3">
                                <!-- Contas sugeridas serão carregadas aqui -->
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="selectConta" class="form-label">Ou selecione uma conta:</label>
                            <select class="form-select" id="selectConta">
                                <option value="">Carregando...</option>
                            </select>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="criar" role="tabpanel">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Uma nova conta será criada com os dados da transação.
                        </div>
                        <div id="dadosNovaConta">
                            <!-- Dados da nova conta serão exibidos aqui -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarConciliacao">
                    <i class="fas fa-check"></i> Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let transacaoAtual = null;
let acaoSelecionada = 'vincular';

function abrirModalConciliacao(transacaoId) {
    transacaoAtual = transacaoId;
    
    // Buscar detalhes da transação
    fetch(`/admin/importacao-ofx/buscar-contas/${transacaoId}`)
        .then(response => response.json())
        .then(data => {
            // Preencher sugestões
            const contasSugeridas = document.getElementById('contasSugeridas');
            contasSugeridas.innerHTML = '';
            
            if (data.length > 0) {
                data.forEach(conta => {
                    const item = document.createElement('div');
                    item.className = 'list-group-item list-group-item-action';
                    item.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${conta.descricao}</strong><br>
                                <small>Vencimento: ${formatarData(conta.data_vencimento)} - 
                                       Valor: R$ ${formatarValor(conta.valor)}</small>
                            </div>
                            <button class="btn btn-sm btn-success" 
                                    onclick="selecionarConta(${conta.id})">
                                Selecionar
                            </button>
                        </div>
                    `;
                    contasSugeridas.appendChild(item);
                });
            } else {
                contasSugeridas.innerHTML = '<p class="text-muted">Nenhuma conta sugerida encontrada.</p>';
            }
        });
    
    // Carregar todas as contas no select
    carregarTodasContas();
    
    // Abrir modal
    const modal = new bootstrap.Modal(document.getElementById('modalConciliacao'));
    modal.show();
}

function carregarTodasContas() {
    // Aqui você implementaria a lógica para carregar todas as contas
    // Por enquanto, vamos deixar um placeholder
    const select = document.getElementById('selectConta');
    select.innerHTML = '<option value="">Selecione uma conta...</option>';
}

function selecionarConta(contaId) {
    document.getElementById('selectConta').value = contaId;
}

function ignorarTransacao(transacaoId) {
    if (confirm('Deseja realmente ignorar esta transação?')) {
        conciliarTransacao(transacaoId, 'ignorar', null);
    }
}

function conciliarTransacao(transacaoId, acao, contaId) {
    const dados = {
        acao: acao,
        conta_id: contaId,
        _token: '{{ csrf_token() }}'
    };
    
    fetch(`/admin/importacao-ofx/conciliar/${transacaoId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(dados)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remover linha da tabela
            document.getElementById(`transacao-${transacaoId}`).remove();
            
            // Fechar modal se estiver aberto
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalConciliacao'));
            if (modal) modal.hide();
            
            // Mostrar mensagem de sucesso
            alert('Transação processada com sucesso!');
        } else {
            alert('Erro ao processar transação: ' + data.message);
        }
    })
    .catch(error => {
        alert('Erro ao processar transação');
        console.error(error);
    });
}

document.getElementById('btnConfirmarConciliacao').addEventListener('click', function() {
    const tabAtiva = document.querySelector('#tabsConciliacao .nav-link.active').id;
    
    if (tabAtiva === 'vincular-tab') {
        const contaId = document.getElementById('selectConta').value;
        if (!contaId) {
            alert('Por favor, selecione uma conta');
            return;
        }
        conciliarTransacao(transacaoAtual, 'vincular', contaId);
    } else {
        conciliarTransacao(transacaoAtual, 'criar', null);
    }
});

function formatarData(data) {
    const d = new Date(data);
    return d.toLocaleDateString('pt-BR');
}

function formatarValor(valor) {
    return valor.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
}
</script>
@endsection