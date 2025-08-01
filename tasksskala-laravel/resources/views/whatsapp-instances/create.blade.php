@extends('layouts.colaborador')

@section('title', 'Nova Instância WhatsApp')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white mb-2">Nova Instância WhatsApp</h1>
        <p class="text-gray-400">Configure uma nova conexão WhatsApp</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card-dark rounded-lg p-6">
                <h2 class="text-lg font-semibold text-white mb-6">
                    <i class="fab fa-whatsapp mr-2 text-green-400"></i>
                    Criar Nova Instância
                </h2>
                
                @if(session('error'))
                    <div class="bg-red-900 bg-opacity-50 border border-red-500 text-red-300 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('whatsapp-instances.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="instanceName" class="block text-sm font-medium text-gray-300 mb-2">
                            Nome da Instância <span class="text-red-400">*</span>
                        </label>
                        <input type="text" 
                               id="instanceName" 
                               name="instanceName" 
                               value="{{ old('instanceName') }}"
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('instanceName') border-red-500 @enderror"
                               placeholder="Ex: instanciaskala" 
                               required>
                        @error('instanceName')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Use apenas letras minúsculas e números, sem espaços ou caracteres especiais</p>
                    </div>

                    <div>
                        <label for="webhookUrl" class="block text-sm font-medium text-gray-300 mb-2">
                            URL do Webhook (Opcional)
                        </label>
                        <input type="url" 
                               id="webhookUrl" 
                               name="webhookUrl" 
                               value="{{ old('webhookUrl') }}"
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('webhookUrl') border-red-500 @enderror"
                               placeholder="https://webhook.site/...">
                        @error('webhookUrl')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Deixe em branco para usar o webhook padrão do sistema</p>
                    </div>

                    <div class="bg-blue-900 bg-opacity-50 border border-blue-500 text-blue-300 px-4 py-4 rounded-lg">
                        <h5 class="font-semibold mb-2">Informações importantes:</h5>
                        <ul class="text-sm space-y-1">
                            <li>• Após criar a instância, você precisará escanear um QR Code com o WhatsApp</li>
                            <li>• Use o WhatsApp Business para melhores resultados</li>
                            <li>• A instância ficará ativa enquanto o WhatsApp estiver conectado</li>
                            <li>• Se já existir uma instância com o mesmo nome, você receberá um erro</li>
                        </ul>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="btn-primary-dark px-6 py-3 rounded-lg font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Criar Instância
                        </button>
                        <a href="{{ route('whatsapp-instances.index') }}" class="btn-secondary-dark px-6 py-3 rounded-lg font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="card-dark rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4">
                    <i class="fas fa-info-circle mr-2 text-blue-400"></i>
                    Eventos do Webhook
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-2"></i>
                        <span>MESSAGES_UPSERT - Mensagens recebidas/enviadas</span>
                    </div>
                    <div class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-2"></i>
                        <span>QRCODE_UPDATED - Atualização do QR Code</span>
                    </div>
                    <div class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-2"></i>
                        <span>CONTACTS_UPSERT - Novos contatos</span>
                    </div>
                    <div class="flex items-center text-gray-300">
                        <i class="fas fa-check text-green-400 mr-2"></i>
                        <span>GROUPS_UPSERT - Atualizações de grupos</span>
                    </div>
                </div>
            </div>
            
            <div class="card-dark rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4">
                    <i class="fas fa-cog mr-2 text-yellow-400"></i>
                    Configuração Automática
                </h3>
                <p class="text-gray-300 text-sm">
                    O webhook será configurado automaticamente para capturar todos os eventos necessários e armazenar:
                </p>
                <ul class="text-gray-400 text-sm mt-2 space-y-1">
                    <li>• Contatos e grupos</li>
                    <li>• Mensagens de texto</li>
                    <li>• Imagens e áudios</li>
                    <li>• Status de entrega</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection