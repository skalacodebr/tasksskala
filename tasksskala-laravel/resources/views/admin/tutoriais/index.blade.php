@extends('layouts.admin')

@section('title', 'Tutoriais')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-900">Gerenciar Tutoriais</h2>
        <a href="{{ route('admin.tutoriais.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Novo Tutorial
        </a>
    </div>
    
    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.tutoriais.index') }}" class="flex items-center space-x-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filtrar por público</label>
                <select name="publico_alvo" class="block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Todos</option>
                    <option value="colaboradores" {{ request('publico_alvo') == 'colaboradores' ? 'selected' : '' }}>Colaboradores</option>
                    <option value="clientes" {{ request('publico_alvo') == 'clientes' ? 'selected' : '' }}>Clientes</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="ativo" class="block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Todos</option>
                    <option value="1" {{ request('ativo') == '1' ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ request('ativo') == '0' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            <div class="flex space-x-2 items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Filtrar
                </button>
                <a href="{{ route('admin.tutoriais.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Limpar
                </a>
            </div>
        </form>
    </div>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-md">
    @if($tutoriais->count() > 0)
        <ul class="divide-y divide-gray-200">
            @foreach($tutoriais as $tutorial)
            <li>
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1">
                            <div class="flex-shrink-0">
                                <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center overflow-hidden">
                                    @if($tutorial->arquivo_video)
                                        <video class="h-full w-full object-cover" muted>
                                            <source src="{{ Storage::url($tutorial->arquivo_video) }}" type="video/mp4">
                                        </video>
                                    @else
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center">
                                    <p class="text-lg font-medium text-blue-600 truncate">
                                        {{ $tutorial->titulo }}
                                    </p>
                                    <div class="ml-2 flex space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                   {{ $tutorial->publico_alvo == 'colaboradores' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($tutorial->publico_alvo) }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                   {{ $tutorial->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $tutorial->ativo ? 'Ativo' : 'Inativo' }}
                                        </span>
                                        @if($tutorial->ordem > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Ordem: {{ $tutorial->ordem }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if($tutorial->descricao)
                                    <div class="mt-1">
                                        <p class="text-sm text-gray-500 line-clamp-2">
                                            {{ Str::limit($tutorial->descricao, 150) }}
                                        </p>
                                    </div>
                                @endif
                                <div class="mt-2 text-xs text-gray-400">
                                    Criado em {{ $tutorial->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.tutoriais.show', $tutorial) }}" 
                               class="text-blue-600 hover:text-blue-900" title="Visualizar">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.tutoriais.edit', $tutorial) }}" 
                               class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('admin.tutoriais.destroy', $tutorial) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900" 
                                        title="Excluir"
                                        onclick="return confirm('Tem certeza que deseja excluir este tutorial? O arquivo de vídeo será permanentemente removido.')">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    @else
        <div class="px-4 py-8 sm:px-6 text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum tutorial encontrado</h3>
            <p>Comece criando o primeiro tutorial do sistema</p>
            <p class="mt-2">
                <a href="{{ route('admin.tutoriais.create') }}" class="text-blue-600 hover:text-blue-500">Criar o primeiro tutorial</a>
            </p>
        </div>
    @endif
</div>

@if($tutoriais->hasPages())
<div class="mt-6">
    {{ $tutoriais->links() }}
</div>
@endif

<script>
// Preview dos vídeos ao passar o mouse
document.addEventListener('DOMContentLoaded', function() {
    const videos = document.querySelectorAll('video');
    videos.forEach(video => {
        video.addEventListener('mouseenter', function() {
            this.play();
        });
        video.addEventListener('mouseleave', function() {
            this.pause();
            this.currentTime = 0;
        });
    });
});
</script>
@endsection