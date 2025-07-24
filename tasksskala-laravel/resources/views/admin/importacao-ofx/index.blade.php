@extends('admin.layout.app')

@section('title', 'Transações OFX Importadas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Transações OFX Importadas</h5>
                        <div>
                            <a href="{{ route('admin.importacao-ofx.conciliar') }}" class="btn btn-warning">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Pendentes de Conciliação
                            </a>
                            <a href="{{ route('admin.importacao-ofx.create') }}" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Nova Importação
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data</th>
                                    <th>Descrição</th>
                                    <th>Beneficiário</th>
                                    <th>Valor</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th>Conta Vinculada</th>
                                    <th>Importado em</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($importacoes as $transacao)
                                <tr>
                                    <td>{{ $transacao->id }}</td>
                                    <td>{{ $transacao->data_transacao->format('d/m/Y') }}</td>
                                    <td>{{ Str::limit($transacao->descricao, 50) }}</td>
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
                                    <td>
                                        <span class="badge bg-{{ $transacao->status_cor }}">
                                            {{ $transacao->status_formatado }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($transacao->conta_pagar_id)
                                            <a href="{{ route('admin.contas-pagar.show', $transacao->conta_pagar_id) }}">
                                                Conta #{{ $transacao->conta_pagar_id }}
                                            </a>
                                        @elseif($transacao->conta_receber_id)
                                            <a href="{{ route('admin.contas-receber.show', $transacao->conta_receber_id) }}">
                                                Conta #{{ $transacao->conta_receber_id }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $transacao->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <p class="mb-0">Nenhuma transação importada ainda.</p>
                                        <a href="{{ route('admin.importacao-ofx.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-upload"></i> Importar Primeiro Arquivo
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($importacoes->hasPages())
                        <div class="mt-3">
                            {{ $importacoes->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Resumo das Importações -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Importado</h5>
                            <h3 class="text-primary">{{ $importacoes->total() }}</h3>
                            <p class="text-muted">Transações</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Conciliadas</h5>
                            <h3 class="text-success">
                                {{ $importacoes->where('status', 'conciliado')->count() }}
                            </h3>
                            <p class="text-muted">Automaticamente</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Pendentes</h5>
                            <h3 class="text-warning">
                                {{ $importacoes->where('status', 'pendente')->count() }}
                            </h3>
                            <p class="text-muted">Aguardando Conciliação</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Ignoradas</h5>
                            <h3 class="text-secondary">
                                {{ $importacoes->where('status', 'ignorado')->count() }}
                            </h3>
                            <p class="text-muted">Não Processadas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection