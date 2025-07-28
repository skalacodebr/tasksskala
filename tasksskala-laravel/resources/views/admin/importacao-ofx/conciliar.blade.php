@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="card-dark rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-700">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Transações Pendentes de Conciliação</h1>
                <div class="flex gap-2">
                    <a href="{{ route('admin.importacao-ofx.create') }}" class="btn-primary-dark font-bold py-2 px-4 rounded">
                        <i class="fas fa-upload mr-2"></i>Nova Importação
                    </a>
                    <a href="{{ route('admin.importacao-ofx.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-list mr-2"></i>Todas as Transações
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if($transacoesPendentes->isEmpty())
                <div class="bg-blue-900 border border-blue-700 text-blue-300 px-4 py-3 rounded">
                    <i class="fas fa-info-circle mr-2"></i>Não há transações pendentes de conciliação.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700 table-dark-custom">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Descrição</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Beneficiário</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Valor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Conta Bancária</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="card-dark divide-y divide-gray-200">
                            @foreach($transacoesPendentes as $transacao)
                            <tr id="transacao-{{ $transacao->id }}" class="hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-primary-dark">{{ $transacao->data_transacao->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm text-primary-dark">{{ $transacao->descricao }}</td>
                                <td class="px-6 py-4 text-sm text-primary-dark">{{ $transacao->beneficiario }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transacao->tipo_conta == 'pagar' ? 'bg-red-900 text-red-200' : 'bg-green-900 text-green-200' }}">
                                        R$ {{ number_format($transacao->valor, 2, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transacao->tipo_conta == 'pagar' ? 'bg-yellow-900 text-yellow-200' : 'bg-blue-900 text-blue-200' }}">
                                        {{ $transacao->tipo_conta == 'pagar' ? 'A Pagar' : 'A Receber' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-primary-dark">{{ $transacao->conta_bancaria }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button class="btn-primary-dark font-bold py-1 px-3 rounded text-xs mr-2" 
                                            onclick="abrirModalConciliacao({{ $transacao->id }})">
                                        <i class="fas fa-link"></i> Conciliar
                                    </button>
                                    <button class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-xs" 
                                            onclick="ignorarTransacao({{ $transacao->id }})">
                                        <i class="fas fa-eye-slash"></i> Ignorar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transacoesPendentes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Conciliação -->
<div id="modalConciliacao" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md card-dark">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-lg font-semibold">Conciliar Transação</h3>
            <button onclick="fecharModal()" class="text-gray-400 hover:text-muted-dark">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mt-4">
            <div id="detalhesTransacao" class="mb-4 p-4 bg-gray-800 rounded">
                <!-- Detalhes da transação serão carregados aqui -->
            </div>

            <div class="border-b mb-4">
                <nav class="flex space-x-8">
                    <button id="vincular-tab" onclick="mudarAba('vincular')" class="py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        <i class="fas fa-link mr-2"></i>Vincular a Conta Existente
                    </button>
                    <button id="criar-tab" onclick="mudarAba('criar')" class="py-2 px-1 border-b-2 border-transparent font-medium text-sm text-muted-dark hover:text-muted-dark">
                        <i class="fas fa-plus mr-2"></i>Criar Nova Conta
                    </button>
                </nav>
            </div>

            <div id="vincular" class="tab-content">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-muted-dark mb-2">Contas Sugeridas</label>
                    <div id="contasSugeridas" class="space-y-2 max-h-48 overflow-y-auto">
                        <!-- Contas sugeridas serão carregadas aqui -->
                    </div>
                </div>

                <div class="mb-4">
                    <label for="selectConta" class="block text-sm font-medium text-muted-dark mb-2">Ou selecione uma conta:</label>
                    <select class="w-full rounded-md border-gray-600" id="selectConta">
                        <option value="">Carregando...</option>
                    </select>
                </div>
            </div>

            <div id="criar" class="tab-content hidden">
                <div class="bg-blue-900 border border-blue-700 text-blue-300 px-4 py-3 rounded mb-4">
                    <i class="fas fa-info-circle mr-2"></i>Uma nova conta será criada com os dados da transação.
                </div>
                <div id="dadosNovaConta">
                    <!-- Dados da nova conta serão exibidos aqui -->
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
            <button onclick="fecharModal()" class="px-4 py-2 border border-gray-600 rounded-md text-muted-dark hover:bg-gray-800">
                Cancelar
            </button>
            <button onclick="confirmarConciliacao()" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                <i class="fas fa-check mr-2"></i>Confirmar
            </button>
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
    document.getElementById('modalConciliacao').classList.remove('hidden');
    
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
                    item.className = 'p-3 border rounded hover:bg-gray-800 cursor-pointer';
                    item.innerHTML = `
                        <div class="flex justify-between items-center">
                            <div>
                                <strong class="text-sm">${conta.descricao}</strong><br>
                                <small class="text-muted-dark">Vencimento: ${formatarData(conta.data_vencimento)} - 
                                       Valor: R$ ${formatarValor(conta.valor)}</small>
                            </div>
                            <button onclick="selecionarConta(${conta.id})" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">
                                Selecionar
                            </button>
                        </div>
                    `;
                    contasSugeridas.appendChild(item);
                });
            } else {
                contasSugeridas.innerHTML = '<p class="text-muted-dark text-sm">Nenhuma conta sugerida encontrada.</p>';
            }
        });
    
    // Carregar todas as contas no select
    carregarTodasContas();
}

function fecharModal() {
    document.getElementById('modalConciliacao').classList.add('hidden');
}

function mudarAba(aba) {
    acaoSelecionada = aba;
    
    // Atualizar visual das abas
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById(aba).classList.remove('hidden');
    
    // Atualizar botões
    document.querySelectorAll('nav button').forEach(btn => {
        btn.classList.remove('border-blue-500', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-muted-dark');
    });
    
    const botaoAtivo = document.getElementById(aba + '-tab');
    botaoAtivo.classList.remove('border-transparent', 'text-muted-dark');
    botaoAtivo.classList.add('border-blue-500', 'text-blue-600');
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

function confirmarConciliacao() {
    if (acaoSelecionada === 'vincular') {
        const contaId = document.getElementById('selectConta').value;
        if (!contaId) {
            alert('Por favor, selecione uma conta');
            return;
        }
        conciliarTransacao(transacaoAtual, 'vincular', contaId);
    } else {
        conciliarTransacao(transacaoAtual, 'criar', null);
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
            fecharModal();
            
            // Mostrar mensagem de sucesso
            alert('Transação processada com sucesso!');
            
            // Recarregar se não houver mais transações
            if (document.querySelectorAll('tbody tr').length === 0) {
                window.location.reload();
            }
        } else {
            alert('Erro ao processar transação: ' + data.message);
        }
    })
    .catch(error => {
        alert('Erro ao processar transação');
        console.error(error);
    });
}

function formatarData(data) {
    const d = new Date(data);
    return d.toLocaleDateString('pt-BR');
}

function formatarValor(valor) {
    return valor.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
}
</script>
@endsection