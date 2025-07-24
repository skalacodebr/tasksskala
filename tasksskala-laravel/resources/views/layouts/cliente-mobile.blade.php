<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#1e40af">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Skala Cliente">
    <meta name="application-name" content="Skala Cliente">
    <meta name="msapplication-TileColor" content="#1e40af">
    <meta name="msapplication-starturl" content="/cliente/login">
    
    <!-- Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- iOS Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/icon-180x180.png">
    
    <title>@yield('title', 'Skala Cliente')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* Previne scroll bounce no iOS */
        html, body {
            position: fixed;
            overflow: hidden;
            width: 100%;
            height: 100%;
        }
        
        /* Safe area para notch do iPhone */
        .safe-top {
            padding-top: env(safe-area-inset-top);
        }
        
        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }
        
        /* Animações nativas */
        .slide-up {
            animation: slideUp 0.3s ease-out;
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* Feedback tátil */
        .touch-feedback {
            transition: all 0.1s ease;
        }
        
        .touch-feedback:active {
            transform: scale(0.95);
            opacity: 0.8;
        }
        
        /* Scrollbar customizada */
        .custom-scroll::-webkit-scrollbar {
            display: none;
        }
        
        .custom-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Bottom nav shadow */
        .bottom-nav-shadow {
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1), 0 -2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="flex flex-col h-full w-full">
        <!-- Header App Bar -->
        <header class="bg-gradient-to-r from-blue-800 to-blue-900 text-white safe-top">
            <div class="flex items-center justify-between px-4 py-3">
                <div class="flex items-center space-x-3">
                    @if(!request()->routeIs('cliente.dashboard'))
                        <button onclick="history.back()" class="touch-feedback p-2 -ml-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                    @endif
                    <h1 class="text-lg font-semibold">@yield('header-title', 'Skala Cliente')</h1>
                </div>
                
                <!-- Menu de opções -->
                <div class="flex items-center space-x-2">
                    @yield('header-actions')
                    <button onclick="toggleMenu()" class="touch-feedback p-2 -mr-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content Area com scroll -->
        <main class="flex-1 overflow-y-auto custom-scroll bg-white">
            <!-- Mensagens de feedback -->
            @if(session('success'))
                <div class="mx-4 mt-4 p-3 bg-green-100 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mx-4 mt-4 p-3 bg-red-100 border border-red-200 text-red-700 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Conteúdo da página -->
            <div class="pb-20">
                @yield('content')
            </div>
        </main>

        <!-- Bottom Navigation -->
        <nav class="bg-white border-t border-gray-200 bottom-nav-shadow safe-bottom">
            <div class="grid grid-cols-5 gap-1">
                <!-- Dashboard -->
                <a href="{{ route('cliente.dashboard') }}" class="touch-feedback flex flex-col items-center py-2 px-1 {{ request()->routeIs('cliente.dashboard') ? 'text-blue-600' : 'text-gray-600' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs">Início</span>
                </a>

                <!-- Nova Task -->
                <a href="{{ route('cliente.criar-task') }}" class="touch-feedback flex flex-col items-center py-2 px-1 {{ request()->routeIs('cliente.criar-task') ? 'text-blue-600' : 'text-gray-600' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-xs">Nova</span>
                </a>

                <!-- Minhas Tasks -->
                <a href="{{ route('cliente.minhas-tasks') }}" class="touch-feedback flex flex-col items-center py-2 px-1 {{ request()->routeIs('cliente.minhas-tasks') ? 'text-blue-600' : 'text-gray-600' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span class="text-xs">Tasks</span>
                </a>

                <!-- Projetos -->
                <a href="{{ route('cliente.projetos') }}" class="touch-feedback flex flex-col items-center py-2 px-1 {{ request()->routeIs('cliente.projetos') ? 'text-blue-600' : 'text-gray-600' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="text-xs">Projetos</span>
                </a>

                <!-- Mais -->
                <button onclick="toggleMoreMenu()" class="touch-feedback flex flex-col items-center py-2 px-1 text-gray-600">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <span class="text-xs">Mais</span>
                </button>
            </div>
        </nav>
    </div>

    <!-- Menu Dropdown -->
    <div id="dropdownMenu" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" onclick="toggleMenu()">
        <div class="absolute top-16 right-4 bg-white rounded-lg shadow-lg p-4 min-w-[200px]" onclick="event.stopPropagation()">
            <div class="py-2 border-b border-gray-100">
                <p class="text-sm font-semibold text-gray-700">{{ Auth::guard('cliente')->user()->nome ?? 'Cliente' }}</p>
                <p class="text-xs text-gray-500">{{ Auth::guard('cliente')->user()->email ?? '' }}</p>
            </div>
            
            <a href="{{ route('cliente.tickets.index') }}" class="block py-3 text-gray-700 hover:bg-gray-50 rounded">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    <span>Tickets de Suporte</span>
                </div>
            </a>
            
            <a href="{{ route('cliente.feedbacks') }}" class="block py-3 text-gray-700 hover:bg-gray-50 rounded">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span>Feedbacks</span>
                </div>
            </a>
            
            <a href="{{ route('cliente.tutoriais') }}" class="block py-3 text-gray-700 hover:bg-gray-50 rounded">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <span>Tutoriais</span>
                </div>
            </a>
            
            <div class="mt-3 pt-3 border-t border-gray-100">
                <form action="{{ route('cliente.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left py-3 text-red-600 hover:bg-red-50 rounded">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Sair</span>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Menu Mais -->
    <div id="moreMenu" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" onclick="toggleMoreMenu()">
        <div class="absolute bottom-20 left-0 right-0 bg-white rounded-t-2xl shadow-lg p-4 safe-bottom" onclick="event.stopPropagation()">
            <div class="w-12 h-1 bg-gray-300 rounded-full mx-auto mb-4"></div>
            
            <h3 class="text-lg font-semibold mb-4">Mais opções</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('cliente.feedbacks') }}" class="touch-feedback bg-gray-50 rounded-lg p-4 text-center">
                    <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span class="text-sm">Feedbacks</span>
                </a>
                
                <a href="{{ route('cliente.tutoriais') }}" class="touch-feedback bg-gray-50 rounded-lg p-4 text-center">
                    <svg class="w-8 h-8 text-purple-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm">Tutoriais</span>
                </a>
            </div>
            
            <div class="mt-6">
                <form action="{{ route('cliente.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="touch-feedback w-full bg-red-50 text-red-600 rounded-lg py-3 font-medium">
                        Sair da conta
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle menu dropdown
        function toggleMenu() {
            const menu = document.getElementById('dropdownMenu');
            menu.classList.toggle('hidden');
        }
        
        // Toggle more menu
        function toggleMoreMenu() {
            const menu = document.getElementById('moreMenu');
            menu.classList.toggle('hidden');
        }
        
        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('ServiceWorker registrado com sucesso:', registration);
                    })
                    .catch(error => {
                        console.error('Erro ao registrar ServiceWorker:', error);
                    });
            });
        }
        
        // Previne pull-to-refresh
        let lastTouchY = 0;
        document.addEventListener('touchstart', e => {
            lastTouchY = e.touches[0].clientY;
        }, { passive: false });
        
        document.addEventListener('touchmove', e => {
            const touchY = e.touches[0].clientY;
            const touchDiff = touchY - lastTouchY;
            
            if (window.scrollY === 0 && touchDiff > 0) {
                e.preventDefault();
            }
            
            lastTouchY = touchY;
        }, { passive: false });
    </script>
</body>
</html>