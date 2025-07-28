@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="card-dark rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-700">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Transações OFX Importadas</h1>
                <div class="flex gap-2">
                    <a href="{{ route('admin.importacao-ofx.conciliar') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Pendentes
                    </a>
                    <a href="{{ route('admin.importacao-ofx.create') }}" class="btn-primary-dark font-bold py-2 px-4 rounded">
                        <i class="fas fa-upload mr-2"></i>Nova Importação
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="alert-success-dark px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error-dark px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700 table-dark-custom">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Descrição</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Beneficiário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Valor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Conta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-dark uppercase tracking-wider">Importado</th>
                        </tr>
                    </thead>
                    <tbody class="card-dark divide-y divide-gray-200">
                        @forelse($importacoes as $transacao)
                        <tr class="hover:bg-gray-800">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary-dark">{{ $transacao->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary-dark">{{ $transacao->data_transacao->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm text-primary-dark">{{ Str::limit($transacao->descricao, 50) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary-dark">{{ $transacao->beneficiario }}</td>
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $transacao->status_cor }}-100 text-{{ $transacao->status_cor }}-800">
                                    {{ $transacao->status_formatado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($transacao->conta_pagar_id)
                                    <a href="{{ route('admin.contas-pagar.show', $transacao->conta_pagar_id) }}" class="text-blue-600 hover:text-blue-200">
                                        Conta #{{ $transacao->conta_pagar_id }}
                                    </a>
                                @elseif($transacao->conta_receber_id)
                                    <a href="{{ route('admin.contas-receber.show', $transacao->conta_receber_id) }}" class="text-blue-600 hover:text-blue-200">
                                        Conta #{{ $transacao->conta_receber_id }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-dark">{{ $transacao->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center">
                                <p class="text-muted-dark mb-2">Nenhuma transação importada ainda.</p>
                                <a href="{{ route('admin.importacao-ofx.create') }}" class="btn-primary-dark font-bold py-2 px-4 rounded inline-block">
                                    <i class="fas fa-upload mr-2"></i>Importar Primeiro Arquivo
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($importacoes->hasPages())
                <div class="mt-4">
                    {{ $importacoes->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Resumo das Importações -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
        <div class="card-dark rounded-lg shadow p-6 text-center">
            <h3 class="text-lg font-semibold text-muted-dark">Total Importado</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $importacoes->total() }}</p>
            <p class="text-muted-dark text-sm">Transações</p>
        </div>
        <div class="card-dark rounded-lg shadow p-6 text-center">
            <h3 class="text-lg font-semibold text-muted-dark">Conciliadas</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $importacoes->where('status', 'conciliado')->count() }}
            </p>
            <p class="text-muted-dark text-sm">Automaticamente</p>
        </div>
        <div class="card-dark rounded-lg shadow p-6 text-center">
            <h3 class="text-lg font-semibold text-muted-dark">Pendentes</h3>
            <p class="text-3xl font-bold text-yellow-600 mt-2">
                {{ $importacoes->where('status', 'pendente')->count() }}
            </p>
            <p class="text-muted-dark text-sm">Aguardando Conciliação</p>
        </div>
        <div class="card-dark rounded-lg shadow p-6 text-center">
            <h3 class="text-lg font-semibold text-muted-dark">Ignoradas</h3>
            <p class="text-3xl font-bold text-muted-dark mt-2">
                {{ $importacoes->where('status', 'ignorado')->count() }}
            </p>
            <p class="text-muted-dark text-sm">Não Processadas</p>
        </div>
    </div>
</div>
@endsection