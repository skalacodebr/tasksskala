@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detalhes da Conta a Receber</h1>
            <div>
                @if($conta->status == 'pendente')
                    <button onclick="abrirModalReceber()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                        Receber
                    </button>
                @endif
                <a href="{{ route('admin.contas-receber.edit', $conta->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Editar
                </a>
                <a href="{{ route('admin.contas-receber.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Voltar
                </a>
            </div>
        </div>

        <div class="card-dark shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-primary-dark">
                    {{ $conta->descricao }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-muted-dark">
                    Informações detalhadas da conta a receber
                </p>
            </div>
            <div class="border-t border-gray-700">
                <dl>
                    <div class="bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Valor</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">
                            <span class="text-lg font-semibold">R$ {{ number_format($conta->valor, 2, ',', '.') }}</span>
                        </dd>
                    </div>
                    <div class="card-dark px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Status</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $conta->status == 'recebido' ? 'bg-green-900 text-green-200' : '' }}
                                {{ $conta->status == 'pendente' ? 'bg-yellow-900 text-yellow-200' : '' }}
                                {{ $conta->status == 'vencido' ? 'bg-red-900 text-red-200' : '' }}
                                {{ $conta->status == 'cancelado' ? 'bg-gray-800 text-primary-dark' : '' }}">
                                {{ ucfirst($conta->status) }}
                            </span>
                        </dd>
                    </div>
                    <div class="bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Cliente</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">
                            @if($conta->cliente)
                                {{ $conta->cliente->nome }}
                                <br><span class="text-xs text-muted-dark">{{ $conta->cliente->cpf_cnpj }}</span>
                                @if($conta->cliente->email)
                                    <br><span class="text-xs text-muted-dark">{{ $conta->cliente->email }}</span>
                                @endif
                                @if($conta->cliente->telefone)
                                    <br><span class="text-xs text-muted-dark">{{ $conta->cliente->telefone }}</span>
                                @endif
                            @else
                                <span class="text-gray-400">Não definido</span>
                            @endif
                        </dd>
                    </div>
                    <div class="card-dark px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Data de Vencimento</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">
                            {{ $conta->data_vencimento->format('d/m/Y') }}
                            @if($conta->status == 'pendente' && $conta->data_vencimento < now())
                                <span class="text-red-600 text-xs ml-2">(Vencida há {{ $conta->data_vencimento->diffInDays(now()) }} dias)</span>
                            @endif
                        </dd>
                    </div>
                    @if($conta->data_recebimento)
                    <div class="bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Data de Recebimento</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">{{ $conta->data_recebimento->format('d/m/Y') }}</dd>
                    </div>
                    @endif
                    <div class="card-dark px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Tipo</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">
                            {{ ucfirst($conta->tipo) }}
                            @if($conta->parcela_atual && $conta->total_parcelas)
                                - Parcela {{ $conta->parcela_atual }}/{{ $conta->total_parcelas }}
                            @endif
                            @if($conta->periodicidade)
                                - {{ ucfirst($conta->periodicidade) }}
                            @endif
                        </dd>
                    </div>
                    @if($conta->categoria)
                    <div class="bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Categoria</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">{{ $conta->categoria }}</dd>
                    </div>
                    @endif
                    <div class="card-dark px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Conta Bancária</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">
                            @if($conta->contaBancaria)
                                {{ $conta->contaBancaria->nome }} - {{ $conta->contaBancaria->banco }}
                            @else
                                <span class="text-gray-400">Não definida</span>
                            @endif
                        </dd>
                    </div>
                    @if($conta->observacoes)
                    <div class="bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Observações</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">{{ $conta->observacoes }}</dd>
                    </div>
                    @endif
                    <div class="card-dark px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Criado em</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">{{ $conta->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div class="bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Última atualização</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">{{ $conta->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

<!-- Modal Receber Conta -->
@if($conta->status == 'pendente')
<div id="modalReceber" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom card-dark rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.contas-receber.receber', $conta->id) }}" method="POST">
                @csrf
                <div class="card-dark px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-primary-dark mb-4">Confirmar Recebimento</h3>
                    <div class="mb-4">
                        <label class="block text-muted-dark text-sm font-bold mb-2">Data do Recebimento</label>
                        <input type="date" name="data_recebimento" required class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-4">
                        <label class="block text-muted-dark text-sm font-bold mb-2">Conta Bancária</label>
                        <select name="conta_bancaria_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-muted-dark leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Selecione uma conta</option>
                            @foreach(\App\Models\ContaBancaria::where('ativo', true)->get() as $contaBancaria)
                                <option value="{{ $contaBancaria->id }}" {{ $conta->conta_bancaria_id == $contaBancaria->id ? 'selected' : '' }}>
                                    {{ $contaBancaria->nome }} - {{ $contaBancaria->banco }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirmar Recebimento
                    </button>
                    <button type="button" onclick="fecharModalReceber()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 card-dark text-base font-medium text-muted-dark hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalReceber() {
    document.getElementById('modalReceber').classList.remove('hidden');
}

function fecharModalReceber() {
    document.getElementById('modalReceber').classList.add('hidden');
}
</script>
@endif
@endsection