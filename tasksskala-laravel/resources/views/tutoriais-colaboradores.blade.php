@extends('layouts.colaborador')

@section('title', 'Tutoriais')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tutoriais</h1>
                    <p class="text-gray-600 mt-1">Aprenda como usar as funcionalidades do sistema</p>
                </div>
                <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>

    @if($tutoriais->count() > 0)
        <!-- Lista de Tutoriais -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tutoriais as $tutorial)
                <div class="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Thumbnail do Vídeo -->
                    <div class="relative bg-gray-900 aspect-video cursor-pointer" onclick="playVideo('video-{{ $tutorial->id }}')">
                        <video id="video-{{ $tutorial->id }}" class="w-full h-full object-cover" preload="metadata" 
                               poster="" muted onclick="event.stopPropagation(); togglePlay(this)">
                            <source src="{{ Storage::url($tutorial->arquivo_video) }}" type="video/mp4">
                            Seu navegador não suporta vídeos HTML5.
                        </video>
                        
                        <!-- Play Button Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 hover:bg-opacity-20 transition-all duration-300"
                             id="play-overlay-{{ $tutorial->id }}">
                            <div class="bg-white bg-opacity-90 rounded-full p-4 hover:bg-opacity-100 transition-all duration-300">
                                <svg class="w-8 h-8 text-gray-800" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Duration Badge -->
                        <div class="absolute bottom-2 right-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
                            <span id="duration-{{ $tutorial->id }}">--:--</span>
                        </div>
                    </div>
                    
                    <!-- Conteúdo do Card -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $tutorial->titulo }}</h3>
                        
                        @if($tutorial->descricao)
                            <p class="text-gray-600 text-sm mb-3 line-clamp-3">{{ $tutorial->descricao }}</p>
                        @endif
                        
                        <!-- Controles do Vídeo -->
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <div class="flex items-center space-x-2">
                                <button onclick="playVideo('video-{{ $tutorial->id }}')" 
                                        class="text-blue-600 hover:text-blue-800 font-medium">
                                    Assistir Tutorial
                                </button>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="toggleFullscreen('video-{{ $tutorial->id }}')" 
                                        class="text-gray-400 hover:text-gray-600" title="Tela cheia">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Estado Vazio -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-8 sm:p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum tutorial disponível</h3>
                <p class="text-gray-600">Não há tutoriais disponíveis para colaboradores no momento.</p>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Obter duração dos vídeos
    const videos = document.querySelectorAll('video');
    videos.forEach(video => {
        video.addEventListener('loadedmetadata', function() {
            const duration = formatTime(this.duration);
            const videoId = this.id.replace('video-', '');
            document.getElementById(`duration-${videoId}`).textContent = duration;
        });
        
        // Atualizar overlay de play/pause
        video.addEventListener('play', function() {
            const videoId = this.id.replace('video-', '');
            document.getElementById(`play-overlay-${videoId}`).style.display = 'none';
        });
        
        video.addEventListener('pause', function() {
            const videoId = this.id.replace('video-', '');
            document.getElementById(`play-overlay-${videoId}`).style.display = 'flex';
        });
        
        video.addEventListener('ended', function() {
            const videoId = this.id.replace('video-', '');
            document.getElementById(`play-overlay-${videoId}`).style.display = 'flex';
        });
    });
});

function playVideo(videoId) {
    const video = document.getElementById(videoId);
    if (video.paused) {
        // Pausar todos os outros vídeos
        document.querySelectorAll('video').forEach(v => {
            if (v.id !== videoId) {
                v.pause();
            }
        });
        video.play();
    } else {
        video.pause();
    }
}

function togglePlay(video) {
    if (video.paused) {
        // Pausar todos os outros vídeos
        document.querySelectorAll('video').forEach(v => {
            if (v !== video) {
                v.pause();
            }
        });
        video.play();
    } else {
        video.pause();
    }
}

function toggleFullscreen(videoId) {
    const video = document.getElementById(videoId);
    if (video.requestFullscreen) {
        video.requestFullscreen();
    } else if (video.webkitRequestFullscreen) {
        video.webkitRequestFullscreen();
    } else if (video.msRequestFullscreen) {
        video.msRequestFullscreen();
    }
}

function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins}:${secs.toString().padStart(2, '0')}`;
}

// Pausar vídeos quando o usuário sai da página
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        document.querySelectorAll('video').forEach(video => {
            video.pause();
        });
    }
});
</script>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

video::-webkit-media-controls {
    display: none !important;
}

video {
    -webkit-appearance: none;
}

video::-webkit-media-controls-start-playback-button {
    display: none !important;
}
</style>
@endsection