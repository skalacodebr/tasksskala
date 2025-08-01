@extends('layouts.colaborador')

@section('title', 'WhatsApp Chat')

@section('content')
<div class="h-screen flex flex-col bg-gray-900">
    <!-- Header -->
    <div class="bg-gray-800 border-b border-gray-700 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-white">
                <i class="fab fa-whatsapp mr-2 text-green-400"></i>
                WhatsApp Chat
            </h1>
            
            <!-- Seletor de Instância -->
            <div class="flex items-center space-x-4">
                <label class="text-sm text-gray-400">Instância:</label>
                <select id="instanceSelect" class="bg-gray-700 text-white px-3 py-1 rounded border border-gray-600 focus:border-green-500">
                    <option value="">Selecione uma instância</option>
                    @foreach($instances as $instance)
                        <option value="{{ $instance }}" {{ $instanceName == $instance ? 'selected' : '' }}>
                            {{ $instance }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Layout do Chat -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Lista de Contatos -->
        <div class="w-1/3 bg-gray-800 border-r border-gray-700 flex flex-col">
            <!-- Cabeçalho da lista -->
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-lg font-medium text-white">Conversas</h2>
                <p class="text-sm text-gray-400">{{ $contacts->count() }} contatos</p>
            </div>
            
            <!-- Lista de contatos -->
            <div class="flex-1 overflow-y-auto">
                @if($contacts->isNotEmpty())
                    @foreach($contacts as $contact)
                        <div class="contact-item p-4 border-b border-gray-700 cursor-pointer hover:bg-gray-750 transition-colors {{ $selectedContact == $contact->remote_jid ? 'bg-gray-700' : '' }}"
                             data-contact="{{ $contact->remote_jid }}"
                             data-name="{{ $contact->push_name ?: 'Contato sem nome' }}">
                            <div class="flex items-center space-x-3">
                                <!-- Avatar -->
                                <div class="w-12 h-12 rounded-full bg-gray-600 flex items-center justify-center">
                                    @if($contact->profile_pic_url)
                                        <img src="{{ $contact->profile_pic_url }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        @if($contact->is_group)
                                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                                
                                <!-- Info do contato -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-white font-medium truncate">
                                            {{ $contact->push_name ?: 'Contato sem nome' }}
                                            @if($contact->is_group)
                                                <span class="ml-1 text-xs text-green-400">(grupo)</span>
                                            @endif
                                        </h3>
                                        @if($contact->last_message_time)
                                            <span class="text-xs text-gray-400">
                                                {{ date('H:i', $contact->last_message_time) }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($contact->last_message)
                                        <p class="text-sm text-gray-400 truncate mt-1">
                                            @if($contact->last_message_from_me)
                                                <span class="text-blue-400">Você: </span>
                                            @endif
                                            {{ $contact->last_message }}
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-500 truncate mt-1">Nenhuma mensagem</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-8 text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-6a2 2 0 012-2h8z"/>
                        </svg>
                        <p>Nenhum contato encontrado</p>
                        <p class="text-sm mt-1">Selecione uma instância para ver os contatos</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Área do Chat -->
        <div class="flex-1 flex flex-col">
            @if($selectedContact)
                <!-- Cabeçalho do chat -->
                <div class="bg-gray-800 border-b border-gray-700 p-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center">
                            @php
                                $currentContact = $contacts->where('remote_jid', $selectedContact)->first();
                            @endphp
                            @if($currentContact && $currentContact->profile_pic_url)
                                <img src="{{ $currentContact->profile_pic_url }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-white font-medium">
                                {{ $currentContact ? $currentContact->push_name ?: 'Contato sem nome' : 'Contato sem nome' }}
                            </h3>
                            <p class="text-sm text-gray-400">{{ $selectedContact }}</p>
                        </div>
                    </div>
                </div>

                <!-- Mensagens -->
                <div class="flex-1 overflow-y-auto p-4 bg-gray-900" id="messagesContainer">
                    @if($messages->isNotEmpty())
                        @foreach($messages as $message)
                            <div class="mb-4 flex {{ $message->from_me ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->from_me ? 'bg-green-600 text-white' : 'bg-gray-700 text-white' }}">
                                    @if($message->media_url)
                                        @if($message->media_type == 'image')
                                            <img src="{{ $message->media_url }}" alt="Imagem" class="rounded mb-2 max-w-full">
                                        @elseif($message->media_type == 'audio')
                                            <audio controls class="mb-2">
                                                <source src="{{ $message->media_url }}" type="audio/ogg">
                                                <source src="{{ $message->media_url }}" type="audio/mpeg">
                                                Seu navegador não suporta áudio.
                                            </audio>
                                        @endif
                                    @endif
                                    
                                    @if($message->message_text && !in_array($message->message_text, ['[Imagem]', '[Áudio]']))
                                        <p class="text-sm">{{ $message->message_text }}</p>
                                    @endif
                                    
                                    <div class="text-xs {{ $message->from_me ? 'text-green-200' : 'text-gray-400' }} mt-1">
                                        {{ date('H:i', $message->message_timestamp) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-gray-400 mt-8">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p>Nenhuma mensagem ainda</p>
                            <p class="text-sm mt-1">Inicie uma conversa!</p>
                        </div>
                    @endif
                </div>
            @else
                <!-- Estado vazio -->
                <div class="flex-1 flex items-center justify-center bg-gray-900">
                    <div class="text-center text-gray-400">
                        <svg class="w-24 h-24 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <h3 class="text-xl font-medium text-white mb-2">WhatsApp Chat</h3>
                        <p>Selecione uma conversa para começar</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mudança de instância
    document.getElementById('instanceSelect').addEventListener('change', function() {
        const instance = this.value;
        if (instance) {
            window.location.href = `{{ route('whatsapp-chat.index') }}?instance=${instance}`;
        }
    });

    // Clique em contato
    document.querySelectorAll('.contact-item').forEach(item => {
        item.addEventListener('click', function() {
            const contact = this.dataset.contact;
            const instance = document.getElementById('instanceSelect').value;
            if (contact && instance) {
                window.location.href = `{{ route('whatsapp-chat.index') }}?instance=${instance}&contact=${contact}`;
            }
        });
    });

    // Auto-scroll para a última mensagem
    const messagesContainer = document.getElementById('messagesContainer');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
});
</script>

<style>
.bg-gray-750 {
    background-color: #374151;
}
</style>
@endsection