<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Skala ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Modern Sidebar -->
        <div class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white w-72 flex-shrink-0 shadow-2xl">
            <!-- Gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 via-purple-600/5 to-blue-600/10"></div>
            
            <!-- Header -->
            <div class="relative p-6 border-b border-slate-700/50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold bg-gradient-to-r from-white to-slate-300 bg-clip-text text-transparent">Skala ERP</h1>
                        <p class="text-sm text-slate-400">Workspace</p>
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="mt-4 p-3 bg-slate-800/50 rounded-xl border border-slate-700/50 backdrop-blur-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-blue-500 rounded-lg flex items-center justify-center text-white text-sm font-semibold">
                            {{ substr(session('colaborador')->nome ?? 'C', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">{{ session('colaborador')->nome ?? 'Colaborador' }}</p>
                            <p class="text-xs text-slate-400">{{ session('colaborador')->setor->nome ?? 'Setor' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="relative p-4 flex flex-col">
                <div class="flex-1 space-y-2">
                    <!-- Main Section -->
                    <div class="mb-6">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 px-3">Principal</p>
                        
                        <a class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 hover:bg-slate-800/50 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-500/20 to-purple-500/20 border border-blue-500/30 shadow-lg' : '' }}" href="{{ route('dashboard') }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gradient-to-br from-blue-500 to-purple-600 shadow-lg' : 'bg-slate-700/50 group-hover:bg-slate-600/50' }} transition-all duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2zM3 7l9 6 9-6"></path>
                                </svg>
                            </div>
                            <span class="ml-3 font-medium group-hover:text-white transition-colors {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-300' }}">Dashboard</span>
                            @if(request()->routeIs('dashboard'))
                                <div class="ml-auto w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                            @endif
                        </a>

                        <a class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 hover:bg-slate-800/50 {{ request()->routeIs('plano-diario') ? 'bg-gradient-to-r from-purple-500/20 to-pink-500/20 border border-purple-500/30 shadow-lg' : '' }}" href="{{ route('plano-diario') }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('plano-diario') ? 'bg-gradient-to-br from-purple-500 to-pink-600 shadow-lg' : 'bg-slate-700/50 group-hover:bg-slate-600/50' }} transition-all duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <span class="font-medium group-hover:text-white transition-colors {{ request()->routeIs('plano-diario') ? 'text-white' : 'text-slate-300' }}">Plano Diário</span>
                                <p class="text-xs text-slate-500">Foco & Produtividade</p>
                            </div>
                            @if(request()->routeIs('plano-diario'))
                                <div class="ml-auto w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
                            @endif
                        </a>

                        <a class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 hover:bg-slate-800/50 {{ request()->routeIs('agente-skala.*') ? 'bg-gradient-to-r from-cyan-500/20 to-blue-500/20 border border-cyan-500/30 shadow-lg' : '' }}" href="{{ route('agente-skala.index') }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('agente-skala.*') ? 'bg-gradient-to-br from-cyan-500 to-blue-600 shadow-lg' : 'bg-slate-700/50 group-hover:bg-slate-600/50' }} transition-all duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <span class="font-medium group-hover:text-white transition-colors {{ request()->routeIs('agente-skala.*') ? 'text-white' : 'text-slate-300' }}">Agente Skala</span>
                                <p class="text-xs text-slate-500">Assistente IA</p>
                            </div>
                            @if(request()->routeIs('agente-skala.*'))
                                <div class="ml-auto w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></div>
                            @endif
                        </a>

                        <a class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 hover:bg-slate-800/50 {{ request()->routeIs('tutoriais') ? 'bg-gradient-to-r from-pink-500/20 to-red-500/20 border border-pink-500/30 shadow-lg' : '' }}" href="{{ route('tutoriais') }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('tutoriais') ? 'bg-gradient-to-br from-pink-500 to-red-600 shadow-lg' : 'bg-slate-700/50 group-hover:bg-slate-600/50' }} transition-all duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <span class="font-medium group-hover:text-white transition-colors {{ request()->routeIs('tutoriais') ? 'text-white' : 'text-slate-300' }}">Tutoriais</span>
                                <p class="text-xs text-slate-500">Aprenda o sistema</p>
                            </div>
                            @if(request()->routeIs('tutoriais'))
                                <div class="ml-auto w-2 h-2 bg-pink-400 rounded-full animate-pulse"></div>
                            @endif
                        </a>
                    </div>

                    <!-- Tasks Section -->
                    <div class="mb-6">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 px-3">Tarefas</p>
                        
                        <a class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 hover:bg-slate-800/50 {{ request()->routeIs('minhas-tarefas') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/20 border border-emerald-500/30 shadow-lg' : '' }}" href="{{ route('minhas-tarefas') }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('minhas-tarefas') ? 'bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg' : 'bg-slate-700/50 group-hover:bg-slate-600/50' }} transition-all duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <span class="ml-3 font-medium group-hover:text-white transition-colors {{ request()->routeIs('minhas-tarefas') ? 'text-white' : 'text-slate-300' }}">Minhas Tarefas</span>
                            @if(request()->routeIs('minhas-tarefas'))
                                <div class="ml-auto w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            @endif
                        </a>

                        <a class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 hover:bg-slate-800/50 {{ request()->routeIs('tarefa.criar') ? 'bg-gradient-to-r from-orange-500/20 to-red-500/20 border border-orange-500/30 shadow-lg' : '' }}" href="{{ route('tarefa.criar') }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('tarefa.criar') ? 'bg-gradient-to-br from-orange-500 to-red-600 shadow-lg' : 'bg-slate-700/50 group-hover:bg-slate-600/50' }} transition-all duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <span class="ml-3 font-medium group-hover:text-white transition-colors {{ request()->routeIs('tarefa.criar') ? 'text-white' : 'text-slate-300' }}">Criar Tarefa</span>
                            @if(request()->routeIs('tarefa.criar'))
                                <div class="ml-auto w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
                            @endif
                        </a>
                    </div>

                    <!-- Projects Section -->
                    <div class="mb-6">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 px-3">Projetos</p>
                        
                        <a class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 hover:bg-slate-800/50 {{ request()->routeIs('projetos.*') ? 'bg-gradient-to-r from-indigo-500/20 to-blue-500/20 border border-indigo-500/30 shadow-lg' : '' }}" href="{{ route('projetos.index') }}">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg {{ request()->routeIs('projetos.*') ? 'bg-gradient-to-br from-indigo-500 to-blue-600 shadow-lg' : 'bg-slate-700/50 group-hover:bg-slate-600/50' }} transition-all duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <span class="ml-3 font-medium group-hover:text-white transition-colors {{ request()->routeIs('projetos.*') ? 'text-white' : 'text-slate-300' }}">Projetos</span>
                            @if(request()->routeIs('projetos.*'))
                                <div class="ml-auto w-2 h-2 bg-indigo-400 rounded-full animate-pulse"></div>
                            @endif
                        </a>
                    </div>
                      <!-- Bottom Section -->
                <div class="mt-6 pt-4 border-t border-slate-700/50">
                    <div class="p-3 bg-gradient-to-r from-red-500/10 to-pink-500/10 border border-red-500/20 rounded-xl backdrop-blur-sm">
                        <form action="{{ route('colaborador.logout') }}" method="POST">
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
                    <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
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