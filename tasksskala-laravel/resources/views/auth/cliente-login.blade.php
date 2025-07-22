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
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Skala Cliente">
    <meta name="application-name" content="Skala Cliente">
    
    <!-- Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- iOS Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/icon-180x180.png">
    
    <title>Skala Cliente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* Previne zoom no iOS */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        textarea {
            font-size: 16px !important;
        }
        
        /* Safe area para notch do iPhone */
        .safe-top {
            padding-top: env(safe-area-inset-top);
        }
        
        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }
        
        /* Animação de entrada */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
        
        /* Loading spinner */
        .spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #1e40af;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-b from-blue-50 to-white min-h-screen flex flex-col">
    <!-- Status Bar Space -->
    <div class="safe-top bg-gradient-to-b from-blue-100 to-blue-50"></div>
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col justify-center px-6 py-8">
        <!-- Logo Section -->
        <div class="text-center mb-8 animate-fade-in-up">
            <div class="w-24 h-24 bg-gradient-to-br from-blue-700 to-blue-900 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <span class="text-white text-4xl font-bold">S</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Skala Cliente</h1>
            <p class="text-gray-600">Entre para gerenciar suas solicitações</p>
        </div>

        <!-- Login Form -->
        <div class="w-full max-w-sm mx-auto animate-fade-in-up" style="animation-delay: 0.1s">
            <form method="POST" action="{{ route('cliente.login') }}" id="loginForm">
                @csrf
                
                <!-- Email Field -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email"
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                               placeholder="seu@email.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mb-6">
                    <input type="checkbox" 
                           name="remember" 
                           id="remember"
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="remember" class="ml-2 text-sm text-gray-700">Manter conectado</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        id="submitBtn"
                        class="w-full bg-gradient-to-r from-blue-700 to-blue-900 text-white py-3.5 px-4 rounded-xl font-semibold hover:from-blue-800 hover:to-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition-all duration-200 active:scale-95">
                    <span id="btnText">Entrar</span>
                    <div id="btnLoader" class="spinner mx-auto hidden"></div>
                </button>
            </form>

            <!-- Error Messages -->
            @if(session('error'))
                <div class="mt-4 p-3 bg-red-100 border border-red-200 text-red-700 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 animate-fade-in-up" style="animation-delay: 0.2s">
            <p class="text-sm text-gray-600">
                Problemas para acessar?
            </p>
            <a href="mailto:suporte@skalacode.com" class="text-sm text-blue-600 font-medium">
                suporte@skalacode.com
            </a>
        </div>
    </div>

    <!-- Bottom Safe Area -->
    <div class="safe-bottom"></div>

    <script>
        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('ServiceWorker registrado');
                    })
                    .catch(error => {
                        console.error('Erro ao registrar ServiceWorker:', error);
                    });
            });
        }

        // Form submission loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            
            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');
        });

        // Auto-focus email field
        window.addEventListener('load', () => {
            setTimeout(() => {
                document.getElementById('email').focus();
            }, 500);
        });
    </script>
</body>
</html>