@extends('layouts.admin')

@section('title', 'Visualizar Tutorial')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $tutorial->titulo }}</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.tutoriais.edit', $tutorial) }}" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Editar
                    </a>
                    <a href="{{ route('admin.tutoriais.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Voltar
                    </a>
                </div>
            </div>
            
            <!-- Informações do Tutorial -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2">
                    <!-- Vídeo -->
                    @if($tutorial->arquivo_video)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vídeo</label>
                            <video controls class="w-full rounded-lg shadow-lg">
                                <source src="{{ Storage::url($tutorial->arquivo_video) }}" type="video/mp4">
                                Seu navegador não suporta o elemento de vídeo.
                            </video>
                        </div>
                    @endif
                    
                    <!-- Descrição -->
                    @if($tutorial->descricao)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $tutorial->descricao }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="space-y-6">
                    <!-- Status e Informações -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Informações</h4>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                           {{ $tutorial->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $tutorial->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Público Alvo</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                           {{ $tutorial->publico_alvo == 'colaboradores' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($tutorial->publico_alvo) }}
                                </span>
                            </div>
                            
                            @if($tutorial->ordem > 0)
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Ordem</label>
                                    <p class="text-sm text-gray-900">{{ $tutorial->ordem }}</p>
                                </div>
                            @endif
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Criado em</label>
                                <p class="text-sm text-gray-900">{{ $tutorial->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            @if($tutorial->updated_at != $tutorial->created_at)
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Atualizado em</label>
                                    <p class="text-sm text-gray-900">{{ $tutorial->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Ações -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Ações</h4>
                        
                        <div class="space-y-2">
                            <a href="{{ route('admin.tutoriais.edit', $tutorial) }}" 
                               class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-center block">
                                Editar Tutorial
                            </a>
                            
                            @if($tutorial->arquivo_video)
                                <a href="{{ Storage::url($tutorial->arquivo_video) }}" 
                                   download="{{ $tutorial->titulo }}.mp4"
                                   class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block">
                                    Download do Vídeo
                                </a>
                            @endif
                            
                            <form action="{{ route('admin.tutoriais.destroy', $tutorial) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="return confirm('Tem certeza que deseja excluir este tutorial? Esta ação não pode ser desfeita e o arquivo de vídeo será permanentemente removido.')">
                                    Excluir Tutorial
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection