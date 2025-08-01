@extends('layouts.colaborador')

@section('title', 'Detalhes da Instância')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white mb-2">Instância: {{ $instanceName }}</h1>
        <p class="text-gray-400">Gerencie a conexão WhatsApp</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card-dark rounded-lg p-6">
                <h2 class="text-lg font-semibold text-white mb-6">
                    <i class="fab fa-whatsapp mr-2"></i>
                    Status da Conexão
                </h2>
                
                @if(session('success'))
                    <div class="bg-green-900 bg-opacity-50 border border-green-500 text-green-300 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-900 bg-opacity-50 border border-red-500 text-red-300 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="text-center">
                    @if($status == 'connected')
                        <div class="mb-6">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-900 bg-opacity-50 rounded-full mb-4">
                                <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-green-400 mb-2">Conectado</h3>
                            <p class="text-gray-300">A instância está conectada e pronta para uso!</p>
                        </div>
                        
                        <form action="{{ route('whatsapp-instances.disconnect', $instanceName) }}" 
                              method="POST" class="inline">
                            @csrf
                            @method('POST')
                            <button type="submit" 
                                    class="btn-secondary-dark px-6 py-3 rounded"
                                    onclick="return confirm('Desconectar esta instância?')">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                Desconectar
                            </button>
                        </form>
                    @elseif($status == 'waiting_qr' && (isset($base64) || isset($qrCode)))
                        <div class="mb-6">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-900 bg-opacity-50 rounded-full mb-4">
                                <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-yellow-400 mb-4">Aguardando Conexão</h3>
                            <p class="text-gray-300 mb-6">Escaneie o QR Code abaixo com seu WhatsApp:</p>
                        </div>
                        
                        <div class="qr-code-container mb-6">
                            @if(isset($base64) && $base64)
                                <img src="{{ $base64 }}" alt="QR Code" class="mx-auto rounded-lg shadow-xl" style="max-width: 350px;">
                            @elseif(isset($qrCode) && $qrCode)
                                <div class="bg-white p-4 rounded-lg inline-block">
                                    <div class="text-black font-mono text-xs break-all" style="max-width: 350px;">
                                        {{ $qrCode }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="bg-gray-800 rounded-lg p-4 text-left max-w-md mx-auto">
                            <p class="text-sm font-semibold text-gray-300 mb-2">Como conectar:</p>
                            <ol class="text-sm text-gray-400 space-y-1">
                                <li>1. Abra o WhatsApp no seu celular</li>
                                <li>2. Vá em <strong>Configurações > Aparelhos conectados</strong></li>
                                <li>3. Clique em <strong>"Conectar um aparelho"</strong></li>
                                <li>4. Escaneie o QR Code acima</li>
                            </ol>
                        </div>
                        
                        <button class="btn-primary-dark px-6 py-3 rounded mt-6" onclick="location.reload()">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Atualizar Status
                        </button>
                    @else
                        <div class="mb-6">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-red-900 bg-opacity-50 rounded-full mb-4">
                                <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-red-400 mb-2">Desconectado</h3>
                            <p class="text-gray-300">A instância não está conectada.</p>
                        </div>
                        
                        <button class="btn-primary-dark px-6 py-3 rounded" onclick="location.reload()">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Tentar Conectar
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="card-dark rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informações
                </h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-400">Nome:</dt>
                        <dd class="text-sm font-medium text-white">{{ $instanceName }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-400">Integração:</dt>
                        <dd class="text-sm font-medium text-white">WHATSAPP-BAILEYS</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-400">Status:</dt>
                        <dd class="text-sm font-medium">
                            @if($status == 'connected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 bg-opacity-50 text-green-400">
                                    <span class="w-2 h-2 mr-1 bg-green-400 rounded-full"></span>
                                    Conectado
                                </span>
                            @elseif($status == 'waiting_qr')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900 bg-opacity-50 text-yellow-400">
                                    <span class="w-2 h-2 mr-1 bg-yellow-400 rounded-full animate-pulse"></span>
                                    Aguardando QR
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 bg-opacity-50 text-red-400">
                                    <span class="w-2 h-2 mr-1 bg-red-400 rounded-full"></span>
                                    Desconectado
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
            
            <div class="card-dark rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Ações
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('whatsapp-instances.index') }}" 
                       class="btn-secondary-dark px-4 py-2 rounded w-full text-center block">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar para Lista
                    </a>
                    
                    <form action="{{ route('whatsapp-instances.destroy', $instanceName) }}" 
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full text-red-400 hover:text-red-300 border border-red-400 hover:border-red-300 px-4 py-2 rounded transition-colors"
                                onclick="return confirm('Tem certeza que deseja remover esta instância? Esta ação não pode ser desfeita.')">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Remover Instância
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh a página a cada 5 segundos se estiver aguardando QR
@if($status == 'waiting_qr')
setTimeout(function() {
    location.reload();
}, 5000);
@endif
</script>
@endsection