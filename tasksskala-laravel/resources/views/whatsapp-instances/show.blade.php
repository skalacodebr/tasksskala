@extends('layouts.colaborador')

@section('title', 'Detalhes da Instância')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Instância: {{ $instanceName }}</h1>
    
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fab fa-whatsapp me-1"></i>
                    Status da Conexão
                </div>
                <div class="card-body text-center">
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

                    @if($status == 'connected')
                        <div class="alert alert-success">
                            <h4 class="alert-heading">
                                <i class="fas fa-check-circle"></i> Conectado
                            </h4>
                            <p>A instância está conectada e pronta para uso!</p>
                        </div>
                        
                        <div class="mt-4">
                            <form action="{{ route('whatsapp-instances.disconnect', $instanceName) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-warning"
                                        onclick="return confirm('Desconectar esta instância?')">
                                    <i class="fas fa-unlink"></i> Desconectar
                                </button>
                            </form>
                        </div>
                    @elseif($status == 'waiting_qr' && $qrCode)
                        <div class="alert alert-warning">
                            <h4 class="alert-heading">
                                <i class="fas fa-qrcode"></i> Aguardando Conexão
                            </h4>
                            <p>Escaneie o QR Code abaixo com seu WhatsApp:</p>
                        </div>
                        
                        <div class="qr-code-container my-4">
                            <img src="{{ $qrCode }}" alt="QR Code" class="img-fluid" style="max-width: 400px;">
                        </div>
                        
                        <div class="alert alert-info">
                            <p class="mb-0"><strong>Como conectar:</strong></p>
                            <ol class="mb-0">
                                <li>Abra o WhatsApp no seu celular</li>
                                <li>Vá em Configurações > Aparelhos conectados</li>
                                <li>Clique em "Conectar um aparelho"</li>
                                <li>Escaneie o QR Code acima</li>
                            </ol>
                        </div>
                        
                        <button class="btn btn-primary mt-3" onclick="location.reload()">
                            <i class="fas fa-sync"></i> Atualizar Status
                        </button>
                    @else
                        <div class="alert alert-danger">
                            <h4 class="alert-heading">
                                <i class="fas fa-times-circle"></i> Desconectado
                            </h4>
                            <p>A instância não está conectada.</p>
                        </div>
                        
                        <button class="btn btn-primary mt-3" onclick="location.reload()">
                            <i class="fas fa-sync"></i> Tentar Conectar
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-cog me-1"></i>
                    Ações
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('whatsapp-instances.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar para Lista
                        </a>
                        
                        <form action="{{ route('whatsapp-instances.destroy', $instanceName) }}" 
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Tem certeza que deseja remover esta instância? Esta ação não pode ser desfeita.')">
                                <i class="fas fa-trash"></i> Remover Instância
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Informações
                </div>
                <div class="card-body">
                    <p><strong>Nome:</strong> {{ $instanceName }}</p>
                    <p><strong>Integração:</strong> WHATSAPP-BAILEYS</p>
                    <p><strong>Status:</strong> 
                        @if($status == 'connected')
                            <span class="badge bg-success">Conectado</span>
                        @elseif($status == 'waiting_qr')
                            <span class="badge bg-warning">Aguardando QR</span>
                        @else
                            <span class="badge bg-danger">Desconectado</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh a página a cada 10 segundos se estiver aguardando QR
@if($status == 'waiting_qr')
setTimeout(function() {
    location.reload();
}, 10000);
@endif
</script>
@endsection