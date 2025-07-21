<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal do Cliente') - Skala ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Modern Sidebar -->
        <div class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 text-white w-72 flex-shrink-0 shadow-2xl">
            <!-- Gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/10 via-blue-600/5 to-indigo-600/10"></div>
            
            <!-- Header -->
            <div class="relative p-6 border-b border-blue-700/50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                    
                    </div>
                    <div>
                        <h1 class="text-xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">Portal Cliente</h1>
                        <p class="text-sm text-blue-200">Skala Code</p>
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="mt-4 p-3 bg-blue-800/50 rounded-xl border border-blue-700/50 backdrop-blur-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center text-white text-sm font-semibold">
                            {{ substr(Auth::guard('cliente')->user()->nome ?? 'C', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">{{ Auth::guard('cliente')->user()->nome ?? 'Cliente' }}</p>
                            <p class="text-xs text-blue-200">{{ Auth::guard('cliente')->user()->email ?? 'cliente@email.com' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="relative p-4  flex flex-col">
                <div class="flex-1 space-y-2">
                    <!-- Main Section -->
                    <div class="mb-6">
                        <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider mb-3 px-3">Principal</p>
                        
                        <a class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 hover:bg-blue-800/50 {{ request()->routeIs('cliente.dashboard') ? 'bg-gradient-to-r from-blue-500/20 to-indigo-500/20 border border-blue-500/30 shadow-lg' : '' }}" href="{{ route('cliente.dashboard') }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('cliente.dashboard') ? 'bg-gradient-to-br from-blue-400 to-indigo-500 shadow-lg' : 'bg-blue-700/50 group-hover:bg-blue-600/50' }} transition-all duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2zM3 7l9 6 9-6"></path>
                                </svg>
                            </div>
                            <span class="ml-3 font-medium group-hover:text-white transition-colors {{ request()->routeIs('cliente.dashboard') ? 'text-white' : 'text-blue-200' }}">Dashboard</span>
                            @if(request()->routeIs('cliente.dashboard'))
                                <div class="ml-auto w-2 h-2 bg-blue-300 rounded-full animate-pulse"></div>
                            @endif
                        </a>
                    </div>

                    <!-- Tasks Section -->
                    <div class="mb-6">
                        <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider mb-3 px-3">Solicitações</p>
                        
                        <a class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 hover:bg-blue-800/50 {{ request()->routeIs('cliente.criar-task') ? 'bg-gradient-to-r from-green-500/20 to-emerald-500/20 border border-green-500/30 shadow-lg' : '' }}" href="{{ route('cliente.criar-task') }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('cliente.criar-task') ? 'bg-gradient-to-br from-green-500 to-emerald-600 shadow-lg' : 'bg-blue-700/50 group-hover:bg-blue-600/50' }} transition-all duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <span class="font-medium group-hover:text-white transition-colors {{ request()->routeIs('cliente.criar-task') ? 'text-white' : 'text-blue-200' }}">Nova Solicitação</span>
                                <p class="text-xs text-blue-400">Correções & Ajustes</p>
                            </div>
                            @if(request()->routeIs('cliente.criar-task'))
                                <div class="ml-auto w-2 h-2 bg-green-300 rounded-full animate-pulse"></div>
                            @endif
                        </a>

                        <a class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 hover:bg-blue-800/50 {{ request()->routeIs('cliente.minhas-tasks') ? 'bg-gradient-to-r from-purple-500/20 to-pink-500/20 border border-purple-500/30 shadow-lg' : '' }}" href="{{ route('cliente.minhas-tasks') }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('cliente.minhas-tasks') ? 'bg-gradient-to-br from-purple-500 to-pink-600 shadow-lg' : 'bg-blue-700/50 group-hover:bg-blue-600/50' }} transition-all duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <span class="ml-3 font-medium group-hover:text-white transition-colors {{ request()->routeIs('cliente.minhas-tasks') ? 'text-white' : 'text-blue-200' }}">Minhas Solicitações</span>
                            @if(request()->routeIs('cliente.minhas-tasks'))
                                <div class="ml-auto w-2 h-2 bg-purple-300 rounded-full animate-pulse"></div>
                            @endif
                        </a>
                    </div>

                    <!-- Projects Section -->
                    <div class="mb-6">
                        <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider mb-3 px-3">Projetos</p>
                        
                        <a class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 hover:bg-blue-800/50 {{ request()->routeIs('cliente.projetos') || request()->routeIs('cliente.projeto.detalhes') ? 'bg-gradient-to-r from-orange-500/20 to-yellow-500/20 border border-orange-500/30 shadow-lg' : '' }}" href="{{ route('cliente.projetos') }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('cliente.projetos') || request()->routeIs('cliente.projeto.detalhes') ? 'bg-gradient-to-br from-orange-500 to-yellow-600 shadow-lg' : 'bg-blue-700/50 group-hover:bg-blue-600/50' }} transition-all duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <span class="font-medium group-hover:text-white transition-colors {{ request()->routeIs('cliente.projetos') || request()->routeIs('cliente.projeto.detalhes') ? 'text-white' : 'text-blue-200' }}">Meus Projetos</span>
                                <p class="text-xs text-blue-400">Acompanhamento</p>
                            </div>
                            @if(request()->routeIs('cliente.projetos') || request()->routeIs('cliente.projeto.detalhes'))
                                <div class="ml-auto w-2 h-2 bg-orange-300 rounded-full animate-pulse"></div>
                            @endif
                        </a>
                    </div>

                    <!-- Support Section -->
                    <div class="mb-6">
                        <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider mb-3 px-3">Ajuda</p>
                        
                        <a class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 hover:bg-blue-800/50 {{ request()->routeIs('cliente.feedbacks*') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/20 border border-emerald-500/30 shadow-lg' : '' }}" href="{{ route('cliente.feedbacks') }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('cliente.feedbacks*') ? 'bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg' : 'bg-blue-700/50 group-hover:bg-blue-600/50' }} transition-all duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <span class="font-medium group-hover:text-white transition-colors {{ request()->routeIs('cliente.feedbacks*') ? 'text-white' : 'text-blue-200' }}">Feedbacks</span>
                                <p class="text-xs text-blue-400">Sugestões e reclamações</p>
                            </div>
                            @if(request()->routeIs('cliente.feedbacks*'))
                                <div class="ml-auto w-2 h-2 bg-emerald-300 rounded-full animate-pulse"></div>
                            @endif
                        </a>
                        
                        <a class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 hover:bg-blue-800/50 {{ request()->routeIs('cliente.tutoriais') ? 'bg-gradient-to-r from-purple-500/20 to-pink-500/20 border border-purple-500/30 shadow-lg' : '' }}" href="{{ route('cliente.tutoriais') }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('cliente.tutoriais') ? 'bg-gradient-to-br from-purple-500 to-pink-600 shadow-lg' : 'bg-blue-700/50 group-hover:bg-blue-600/50' }} transition-all duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <span class="font-medium group-hover:text-white transition-colors {{ request()->routeIs('cliente.tutoriais') ? 'text-white' : 'text-blue-200' }}">Tutoriais</span>
                                <p class="text-xs text-blue-400">Como usar o portal</p>
                            </div>
                            @if(request()->routeIs('cliente.tutoriais'))
                                <div class="ml-auto w-2 h-2 bg-purple-300 rounded-full animate-pulse"></div>
                            @endif
                        </a>
                    </div>
                      <!-- Bottom Section -->
                <div class="mt-6 pt-4 border-t border-blue-700/50">
                    <div class="p-3 bg-gradient-to-r from-red-500/10 to-pink-500/10 border border-red-500/20 rounded-xl backdrop-blur-sm">
                        <form action="{{ route('cliente.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="group w-full flex items-center px-3 py-2 rounded-lg transition-all duration-200 hover:bg-red-500/20">
                                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-red-500/20 group-hover:bg-red-500/30 transition-all duration-200">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <span class="ml-3 text-sm font-medium text-red-300 group-hover:text-red-200 transition-colors">Sair</span>
                            </button>
                        </form>
                    </div>
                </div>
                </div>

              
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Portal do Cliente')</h1>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>