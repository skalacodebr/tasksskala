@extends('admin.layout.app')

@section('title', 'Importar Arquivo OFX')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Importar Arquivo OFX</h5>
                        <a href="{{ route('admin.importacao-ofx.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.importacao-ofx.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="arquivo_ofx" class="form-label">Arquivo OFX</label>
                            <input type="file" class="form-control @error('arquivo_ofx') is-invalid @enderror" 
                                   id="arquivo_ofx" name="arquivo_ofx" accept=".ofx,.xml,.txt" required>
                            @error('arquivo_ofx')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Formatos aceitos: .ofx, .xml, .txt (máximo 5MB)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_conta" class="form-label">Tipo de Conta</label>
                            <select class="form-select @error('tipo_conta') is-invalid @enderror" 
                                    id="tipo_conta" name="tipo_conta" required>
                                <option value="">Selecione o tipo</option>
                                <option value="pagar" {{ old('tipo_conta') == 'pagar' ? 'selected' : '' }}>
                                    Contas a Pagar
                                </option>
                                <option value="receber" {{ old('tipo_conta') == 'receber' ? 'selected' : '' }}>
                                    Contas a Receber
                                </option>
                            </select>
                            @error('tipo_conta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Selecione se as transações do arquivo são contas a pagar ou a receber
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6 class="alert-heading">Como funciona a importação:</h6>
                            <ul class="mb-0">
                                <li>O sistema tentará conciliar automaticamente as transações com contas existentes</li>
                                <li>Transações não conciliadas automaticamente ficarão pendentes para revisão manual</li>
                                <li>Você poderá vincular manualmente ou criar novas contas para as transações pendentes</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.importacao-ofx.index') }}" class="btn btn-secondary me-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Importar Arquivo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection