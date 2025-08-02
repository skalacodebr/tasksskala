<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Skala ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Scrollbar customizada */
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
        
        /* Animações da sidebar */
        .sidebar-collapsed {
            width: 4rem;
        }
        
        .sidebar-expanded {
            width: 16rem;
        }
        
        /* Esconder elementos quando colapsado */
        .sidebar-collapsed .sidebar-text {
            display: none;
        }
        
        .sidebar-expanded .sidebar-text {
            opacity: 1;
            visibility: visible;
        }
        
        /* Centralizar ícones quando colapsado */
        .sidebar-collapsed .sidebar-link {
            justify-content: center;
        }
        
        .sidebar-expanded .sidebar-link {
            justify-content: flex-start;
        }
        
        /* Dropdown específico */
        .sidebar-collapsed .dropdown-chevron {
            display: none;
        }
        
        .sidebar-expanded .dropdown-chevron {
            display: block;
        }
        
        /* Esconder dropdown items quando colapsado */
        .sidebar-collapsed .dropdown-items {
            display: none;
        }
        
        /* Main content area */
        .main-content {
            background-color: #111111;
            color: #e5e5e5;
        }
        
        /* Inputs e forms */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="date"],
        input[type="datetime-local"],
        input[type="time"],
        input[type="search"],
        input[type="tel"],
        input[type="url"],
        select,
        textarea {
            background-color: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #e5e5e5;
        }
        
        input:focus,
        select:focus,
        textarea:focus {
            border-color: rgba(255, 255, 255, 0.4);
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-black">
    <div class="min-h-screen flex">
        <!-- Toggle Button for Mobile -->
        <button id="sidebarToggle" class="fixed top-4 left-4 z-50 md:hidden bg-black text-white p-2 rounded-lg shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Sidebar -->
        <div id="sidebar" class="bg-black text-white sidebar-collapsed md:sidebar-collapsed transition-all duration-300 ease-in-out flex-shrink-0 flex flex-col overflow-hidden fixed md:relative z-40 h-full -translate-x-full md:translate-x-0">
            <!-- Logo -->
            <div class="p-4 flex items-center justify-center">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span class="text-xl font-semibold ml-3 sidebar-text transition-all duration-300">Skala</span>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 px-2 pb-4 sidebar-scroll overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="ml-3 font-medium sidebar-text transition-all duration-300">Dashboard</span>
                </a>

                <!-- Desempenho do Time -->
                <a href="{{ route('desempenho-time') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('desempenho-time') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="ml-3 font-medium sidebar-text transition-all duration-300">Desempenho do Time</span>
                </a>

                @php
                    $colaboradorKanbanSetor = session('colaborador')->setor->nome ?? '';
                @endphp
                @if($colaboradorKanbanSetor === 'Vendas' || $colaboradorKanbanSetor === 'Administrativo')
                <!-- Kanban de Vendas -->
                <a href="{{ route('kanban-vendas.index') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('kanban-vendas.*') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                    </svg>
                    <span class="ml-3 font-medium sidebar-text transition-all duration-300">Kanban de Vendas</span>
                </a>
                @endif
                
                <!-- Reuniões -->
                <a href="{{ route('reunioes.index') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('reunioes.*') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="ml-3 font-medium sidebar-text transition-all duration-300">Reuniões</span>
                </a>

                <!-- Plano Diário -->
                <a href="{{ route('plano-diario') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('plano-diario') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="ml-3 font-medium sidebar-text transition-all duration-300">Plano Diário</span>
                </a>

                <!-- Tarefas Dropdown -->
                <div class="mb-2">
                    <button onclick="toggleDropdown('tarefasDropdown')" class="w-full sidebar-link flex items-center px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs(['minhas-tarefas', 'tarefas-designadas', 'tarefa.*']) ? 'bg-gray-900' : 'hover:bg-gray-900' }} text-gray-400 hover:text-white">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="ml-3 font-medium sidebar-text transition-all duration-300">Tarefas</span>
                        <svg class="w-4 h-4 ml-auto transition-transform dropdown-chevron {{ request()->routeIs(['minhas-tarefas', 'tarefas-designadas', 'tarefa.*']) ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div id="tarefasDropdown" class="mt-1 space-y-1 dropdown-items {{ request()->routeIs(['minhas-tarefas', 'tarefas-designadas', 'tarefa.*']) ? '' : 'hidden' }}">
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
                        
                        @if(session('colaborador')->setor_id === 3 || session('colaborador')->setor->nome === 'Administrativo')
                            <a href="{{ route('todas-tarefas') }}" class="flex items-center pl-11 pr-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('todas-tarefas') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-500 hover:text-white' }}">
                                <span class="text-sm font-medium">Todas as Tarefas</span>
                                <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Projetos -->
                <a href="{{ route('projetos.index') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('projetos.*') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="ml-3 font-medium sidebar-text transition-all duration-300">Projetos</span>
                </a>

                <!-- Agente Skala -->
                <a href="{{ route('agente-skala.index') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('agente-skala.*') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="ml-3 font-medium sidebar-text transition-all duration-300">Agente Skala</span>
                </a>

                <!-- Tickets -->
                <a href="{{ route('tickets.index') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('tickets.*') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    <span class="ml-3 font-medium sidebar-text transition-all duration-300">Tickets</span>
                </a>

                <!-- Tutoriais -->
                <a href="{{ route('tutoriais') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('tutoriais') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="ml-3 font-medium sidebar-text transition-all duration-300">Tutoriais</span>
                </a>

                @php
                    $colaboradorSetor = session('colaborador')->setor->nome ?? '';
                    $colaboradorSetorId = session('colaborador')->setor_id ?? 0;
                @endphp

                @if($colaboradorSetor === 'Administrativo' || $colaboradorSetorId === 3)
                    <!-- Administrativo Section -->
                    <div class="mt-6 pt-6 border-t border-gray-800">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 sidebar-text transition-all duration-300">Administrativo</h3>
                        
                        <!-- WhatsApp Instances -->
                        <a href="{{ route('whatsapp-instances.index') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('whatsapp-instances.*') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span class="ml-3 font-medium sidebar-text transition-all duration-300">WhatsApp API</span>
                        </a>
                        
                        <!-- WhatsApp Chat -->
                        <a href="{{ route('whatsapp-chat.index') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('whatsapp-chat.*') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <i class="fab fa-whatsapp w-5 h-5 flex-shrink-0 text-green-400 text-center"></i>
                            <span class="ml-3 font-medium sidebar-text transition-all duration-300">WhatsApp Chat</span>
                        </a>
                    </div>
                @endif

                @if($colaboradorSetor === 'Administrativo' || $colaboradorSetorId === 3 || $colaboradorSetor === 'Financeiro')
                    <!-- Financeiro Section -->
                    <div class="mt-6 pt-6 border-t border-gray-800">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 sidebar-text transition-all duration-300">Financeiro</h3>
                        
                        <!-- Dashboard Financeira -->
                        <a href="{{ route('financeiro.dashboard') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.dashboard') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="ml-3 font-medium sidebar-text transition-all duration-300">Dashboard Financeira</span>
                        </a>

                        <!-- Fluxo de Caixa -->
                        <a href="{{ route('financeiro.fluxo-caixa') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.fluxo-caixa') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="ml-3 font-medium sidebar-text transition-all duration-300">Fluxo de Caixa</span>
                        </a>

                        <!-- Tipos de Custo -->
                        <a href="{{ route('financeiro.tipos-custo') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.tipos-custo') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="ml-3 font-medium sidebar-text transition-all duration-300">Tipos de Custo</span>
                        </a>

                        <!-- Categorias -->
                        <a href="{{ route('financeiro.categorias') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.categorias') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="ml-3 font-medium sidebar-text transition-all duration-300">Categorias</span>
                        </a>

                        <!-- Contas Bancárias -->
                        <a href="{{ route('financeiro.contas-bancarias') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.contas-bancarias') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <span class="ml-3 font-medium sidebar-text transition-all duration-300">Contas Bancárias</span>
                        </a>

                        <!-- Contas a Pagar -->
                        <a href="{{ route('financeiro.contas-pagar') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.contas-pagar') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="ml-3 font-medium sidebar-text transition-all duration-300">Contas a Pagar</span>
                        </a>

                        <!-- Contas a Receber -->
                        <a href="{{ route('financeiro.contas-receber') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.contas-receber') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="ml-3 font-medium sidebar-text transition-all duration-300">Contas a Receber</span>
                        </a>

                        <!-- Fornecedores -->
                        <a href="{{ route('financeiro.fornecedores') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.fornecedores') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="ml-3 font-medium sidebar-text transition-all duration-300">Fornecedores</span>
                        </a>

                        <!-- Importação OFX -->
                        <a href="{{ route('financeiro.importacao-ofx') }}" class="sidebar-link flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.importacao-ofx') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span class="ml-3 font-medium sidebar-text transition-all duration-300">Importação OFX</span>
                        </a>
                    </div>
                @endif
            </nav>

            <!-- User Section -->
            <div class="p-4 border-t border-gray-800">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                            {{ substr(session('colaborador')->nome ?? 'C', 0, 1) }}
                        </div>
                        <div class="ml-3 sidebar-text transition-all duration-300">
                            <p class="text-sm font-medium">{{ session('colaborador')->nome ?? 'Colaborador' }}</p>
                            <p class="text-xs text-gray-500">{{ session('colaborador')->setor->nome ?? 'Setor' }}</p>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('colaborador.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full sidebar-link flex items-center px-3 py-2 bg-gray-900 hover:bg-gray-800 rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="ml-3 text-sm font-medium sidebar-text transition-all duration-300">Sair</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar -->
            <header class="bg-gray-900 shadow-sm border-b border-gray-800">
                <div class="px-6 py-4">
                    <h1 class="text-2xl font-semibold text-white">@yield('title', 'Dashboard')</h1>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto main-content">
                <div class="px-6 py-6">
                    @if(session('success'))
                        <div class="mb-4 bg-green-900 bg-opacity-50 border border-green-500 text-green-300 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-900 bg-opacity-50 border border-red-500 text-red-300 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        // Toggle dropdown
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const chevron = dropdown.previousElementSibling.querySelector('.dropdown-chevron');
            
            dropdown.classList.toggle('hidden');
            if (chevron) {
                chevron.classList.toggle('rotate-180');
            }
        }

        // Sidebar hover functionality
        const sidebar = document.getElementById('sidebar');
        let hoverTimeout;

        sidebar.addEventListener('mouseenter', function() {
            clearTimeout(hoverTimeout);
            sidebar.classList.remove('sidebar-collapsed');
            sidebar.classList.add('sidebar-expanded');
        });

        sidebar.addEventListener('mouseleave', function() {
            hoverTimeout = setTimeout(() => {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
            }, 300);
        });

        // Mobile sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
            });
            
            // Close sidebar when clicking outside (mobile)
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 768 && 
                    !sidebar.contains(e.target) && 
                    !sidebarToggle.contains(e.target) &&
                    !sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        }

        // Keep dropdown open if on a child page
        document.addEventListener('DOMContentLoaded', function() {
            const activeDropdowns = document.querySelectorAll('.bg-gray-900');
            activeDropdowns.forEach(dropdown => {
                const parent = dropdown.closest('.mb-2');
                if (parent) {
                    const dropdownContent = parent.querySelector('.dropdown-items');
                    if (dropdownContent) {
                        dropdownContent.classList.remove('hidden');
                        const chevron = parent.querySelector('.dropdown-chevron');
                        if (chevron) {
                            chevron.classList.add('rotate-180');
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>