@extends('layouts.colaborador')

@section('title', 'Instâncias WhatsApp')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white mb-2">Instâncias WhatsApp</h1>
        <p class="text-gray-400">Gerencie as conexões WhatsApp da API</p>
    </div>
    
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
    
    <div class="card-dark rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-white">
                <i class="fab fa-whatsapp mr-2"></i>
                Gerenciar Instâncias
            </h2>
            <a href="{{ route('whatsapp-instances.create') }}" class="btn-primary-dark px-4 py-2 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nova Instância
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-dark-custom">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Nome da Instância</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @if(is_array($instances) && count($instances) > 0)
                        @foreach($instances as $instance)
                            @php
                                // Normalizar estrutura de dados
                                $instanceName = $instance['instanceName'] ?? 
                                               $instance['instance'] ?? 
                                               $instance['name'] ?? 
                                               (is_string($instance) ? $instance : 'N/A');
                                $instanceState = $instance['state'] ?? 
                                                $instance['connectionState'] ?? 
                                                $instance['status'] ?? 
                                                'closed';
                            @endphp
                            @if($instanceName !== 'N/A')
                            <tr class="hover:bg-gray-800 transition-colors">
                                <td class="px-4 py-4 text-sm text-gray-300">{{ $instanceName }}</td>
                                <td class="px-4 py-4 text-sm">
                                    @if($instanceState == 'open' || $instanceState == 'connected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 bg-opacity-50 text-green-400">
                                            <span class="w-2 h-2 mr-1 bg-green-400 rounded-full"></span>
                                            Conectado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900 bg-opacity-50 text-yellow-400">
                                            <span class="w-2 h-2 mr-1 bg-yellow-400 rounded-full"></span>
                                            Desconectado
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('whatsapp-instances.show', $instanceName) }}" 
                                           class="text-blue-400 hover:text-blue-300 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        
                                        @if($instanceState == 'open' || $instanceState == 'connected')
                                            <form action="{{ route('whatsapp-instances.disconnect', $instanceName) }}" 
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" 
                                                        class="text-yellow-400 hover:text-yellow-300 transition-colors"
                                                        onclick="return confirm('Desconectar esta instância?')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('whatsapp-instances.destroy', $instanceName) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-400 hover:text-red-300 transition-colors"
                                                    onclick="return confirm('Tem certeza que deseja remover esta instância?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                Nenhuma instância encontrada
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection