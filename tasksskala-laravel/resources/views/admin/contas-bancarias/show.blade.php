@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detalhes da Conta Bancária</h1>
            <div>
                <a href="{{ route('admin.contas-bancarias.edit', $conta->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Editar
                </a>
                <a href="{{ route('admin.contas-bancarias.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Voltar
                </a>
            </div>
        </div>

        <div class="card-dark shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-primary-dark">
                    {{ $conta->nome }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-muted-dark">
                    Informações detalhadas da conta bancária
                </p>
            </div>
            <div class="border-t border-gray-700">
                <dl>
                    <div class="bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Banco</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">{{ $conta->banco }}</dd>
                    </div>
                    <div class="card-dark px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Agência</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">{{ $conta->agencia ?? 'Não informada' }}</dd>
                    </div>
                    <div class="bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Conta</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">{{ $conta->conta }}</dd>
                    </div>
                    <div class="card-dark px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Tipo de Conta</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($conta->tipo_conta) }}
                            </span>
                        </dd>
                    </div>
                    <div class="bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Saldo Atual</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">
                            <span class="text-lg font-semibold">R$ {{ number_format($conta->saldo_atual, 2, ',', '.') }}</span>
                        </dd>
                    </div>
                    <div class="card-dark px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-muted-dark">Status</dt>
                        <dd class="mt-1 text-sm text-primary-dark sm:mt-0 sm:col-span-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $conta->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $conta->ativo ? 'Ativa' : 'Inativa' }}
                            </span>
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

        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Resumo Financeiro</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="card-dark p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-2">Contas a Pagar</h3>
                    <p class="text-muted-dark">Total: {{ $conta->contasPagar()->count() }}</p>
                    <p class="text-muted-dark">Pendentes: {{ $conta->contasPagar()->pendentes()->count() }}</p>
                    <p class="text-muted-dark">Valor total pendente: R$ {{ number_format($conta->contasPagar()->pendentes()->sum('valor'), 2, ',', '.') }}</p>
                </div>
                <div class="card-dark p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-2">Contas a Receber</h3>
                    <p class="text-muted-dark">Total: {{ $conta->contasReceber()->count() }}</p>
                    <p class="text-muted-dark">Pendentes: {{ $conta->contasReceber()->pendentes()->count() }}</p>
                    <p class="text-muted-dark">Valor total pendente: R$ {{ number_format($conta->contasReceber()->pendentes()->sum('valor'), 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection