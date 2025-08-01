@extends('layouts.admin')

@section('title', 'Nova Instância WhatsApp')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Nova Instância WhatsApp</h1>
    
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fab fa-whatsapp me-1"></i>
                    Criar Nova Instância
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.whatsapp-instances.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="instanceName" class="form-label">Nome da Instância <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('instanceName') is-invalid @enderror" 
                                   id="instanceName" name="instanceName" value="{{ old('instanceName') }}"
                                   placeholder="Ex: instanciaskala" required>
                            @error('instanceName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Use apenas letras minúsculas e números, sem espaços ou caracteres especiais</div>
                        </div>

                        <div class="mb-3">
                            <label for="webhookUrl" class="form-label">URL do Webhook (Opcional)</label>
                            <input type="url" class="form-control @error('webhookUrl') is-invalid @enderror" 
                                   id="webhookUrl" name="webhookUrl" value="{{ old('webhookUrl') }}"
                                   placeholder="https://webhook.site/...">
                            @error('webhookUrl')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Deixe em branco para usar o webhook padrão</div>
                        </div>

                        <div class="alert alert-info">
                            <h5 class="alert-heading">Informações importantes:</h5>
                            <ul class="mb-0">
                                <li>Após criar a instância, você precisará escanear um QR Code com o WhatsApp</li>
                                <li>Use o WhatsApp Business para melhores resultados</li>
                                <li>A instância ficará ativa enquanto o WhatsApp estiver conectado</li>
                                <li>Se já existir uma instância com o mesmo nome, você receberá um erro</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Criar Instância
                            </button>
                            <a href="{{ route('admin.whatsapp-instances.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Eventos do Webhook
                </div>
                <div class="card-body">
                    <p>Os seguintes eventos serão capturados:</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-1"></i> MESSAGES_UPSERT - Mensagens recebidas/enviadas</li>
                        <li><i class="fas fa-check text-success me-1"></i> QRCODE_UPDATED - Atualização do QR Code</li>
                        <li><i class="fas fa-check text-success me-1"></i> CONTACTS_UPSERT - Novos contatos</li>
                        <li><i class="fas fa-check text-success me-1"></i> GROUPS_UPSERT - Atualizações de grupos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection