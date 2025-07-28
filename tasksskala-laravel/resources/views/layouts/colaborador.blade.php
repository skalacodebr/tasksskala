<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Skala ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 3px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Modern Sidebar -->
        <div class="relative bg-black text-white w-64 flex-shrink-0 flex flex-col">
            <!-- Logo -->
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-semibold">Skala</span>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 px-4 pb-4 sidebar-scroll overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- Plano Diário -->
                <a href="{{ route('plano-diario') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('plano-diario') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Plano Diário</span>
                </a>

                <!-- Tarefas Dropdown -->
                <div class="mb-2">
                    <button onclick="toggleDropdown('tarefasDropdown')" class="w-full flex items-center justify-between px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs(['minhas-tarefas', 'tarefas-designadas', 'tarefa.*']) ? 'bg-gray-900' : 'hover:bg-gray-900' }} text-gray-400 hover:text-white">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span class="font-medium">Tarefas</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform {{ request()->routeIs(['minhas-tarefas', 'tarefas-designadas', 'tarefa.*']) ? 'rotate-180' : '' }}" id="tarefasChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div id="tarefasDropdown" class="mt-1 space-y-1 {{ request()->routeIs(['minhas-tarefas', 'tarefas-designadas', 'tarefa.*']) ? '' : 'hidden' }}">
                        <a href="{{ route('minhas-tarefas') }}" class="flex items-center pl-11 pr-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('minhas-tarefas') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-500 hover:text-white' }}">
                            <span class="text-sm font-medium">Minhas Tarefas</span>
                            @php
                                $tarefasPendentes = \App\Models\Tarefa::where('colaborador_id', session('colaborador')->id ?? 0)
                                    ->whereIn('status', ['pendente', 'em_andamento'])
                                    ->count();
                            @endphp
                            @if($tarefasPendentes > 0)
                                <span class="ml-auto bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $tarefasPendentes }}</span>
                            @endif
                        </a>
                        
                        <a href="{{ route('tarefas-designadas') }}" class="flex items-center pl-11 pr-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('tarefas-designadas') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-500 hover:text-white' }}">
                            <span class="text-sm font-medium">Tarefas Designadas</span>
                        </a>
                        
                        <a href="{{ route('tarefa.criar') }}" class="flex items-center pl-11 pr-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('tarefa.criar') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-500 hover:text-white' }}">
                            <span class="text-sm font-medium">Criar Tarefa</span>
                            <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Projetos -->
                <a href="{{ route('projetos.index') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('projetos.*') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="font-medium">Projetos</span>
                </a>

                <!-- Agente Skala -->
                <a href="{{ route('agente-skala.index') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('agente-skala.*') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium">Agente Skala</span>
                </a>

                <!-- Tickets -->
                <a href="{{ route('tickets.index') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('tickets.*') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    <span class="font-medium">Tickets</span>
                </a>

                <!-- Tutoriais -->
                <a href="{{ route('tutoriais') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('tutoriais') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="font-medium">Tutoriais</span>
                </a>
            </nav>

            <!-- User Section -->
            <div class="p-4 border-t border-gray-800">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                            {{ substr(session('colaborador')->nome ?? 'C', 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('colaborador')->nome ?? 'Colaborador' }}</p>
                            <p class="text-xs text-gray-500">{{ session('colaborador')->setor->nome ?? 'Setor' }}</p>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('colaborador.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-3 py-2 bg-gray-900 hover:bg-gray-800 rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="text-sm font-medium">Sair</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="px-6 py-6">
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

    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const chevron = document.getElementById(id.replace('Dropdown', 'Chevron'));
            
            dropdown.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        // Keep dropdown open if on a child page
        document.addEventListener('DOMContentLoaded', function() {
            const activeDropdowns = document.querySelectorAll('.bg-gray-900');
            activeDropdowns.forEach(dropdown => {
                const parent = dropdown.closest('.mb-2');
                if (parent) {
                    const dropdownContent = parent.querySelector('[id$="Dropdown"]');
                    if (dropdownContent) {
                        dropdownContent.classList.remove('hidden');
                    }
                }
            });
        });
    </script>
</body>
</html>