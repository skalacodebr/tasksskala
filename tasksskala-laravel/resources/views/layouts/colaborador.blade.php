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
        
        /* Main content area following sidebar visual identity */
        .main-content {
            background-color: #111111;
            color: #e5e5e5;
        }
        
        /* Custom scrollbar for main content */
        .main-content::-webkit-scrollbar {
            width: 8px;
        }
        .main-content::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        .main-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }
        .main-content::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        /* Header styling */
        .header-dark {
            background-color: #1a1a1a;
            border-bottom-color: rgba(255, 255, 255, 0.1);
        }
        
        /* Card and panel styling */
        .card-dark {
            background-color: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Input and form elements */
        .input-dark {
            background-color: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #e5e5e5;
        }
        
        .input-dark:focus {
            border-color: rgba(255, 255, 255, 0.4);
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }
        
        /* Button styling following sidebar pattern */
        .btn-primary-dark {
            background-color: white;
            color: black;
            font-weight: 500;
        }
        
        .btn-primary-dark:hover {
            background-color: #f0f0f0;
        }
        
        .btn-secondary-dark {
            background-color: transparent;
            color: #e5e5e5;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn-secondary-dark:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        /* Table styling */
        .table-dark-custom thead {
            background-color: #1a1a1a;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .table-dark-custom tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .table-dark-custom tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        /* Text color utilities */
        .text-muted-dark {
            color: #9ca3af;
        }
        
        .text-primary-dark {
            color: #e5e5e5;
        }
        
        /* Alert styling */
        .alert-success-dark {
            background-color: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
        }
        
        .alert-error-dark {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        
        /* Global dark theme for all form elements */
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
        textarea,
        .border-gray-600 {
            background-color: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #e5e5e5;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        input[type="datetime-local"]:focus,
        input[type="time"]:focus,
        input[type="search"]:focus,
        input[type="tel"]:focus,
        input[type="url"]:focus,
        select:focus,
        textarea:focus,
        .border-gray-600:focus {
            border-color: rgba(255, 255, 255, 0.4);
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }
        
        /* Placeholder text color */
        input::placeholder,
        textarea::placeholder {
            color: #6b7280;
        }
        
        /* Checkbox and radio styling */
        input[type="checkbox"],
        input[type="radio"] {
            background-color: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        input[type="checkbox"]:checked,
        input[type="radio"]:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        
        /* Option styling for selects */
        option {
            background-color: #1a1a1a;
            color: #e5e5e5;
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

        <!-- Modern Sidebar -->
        <div id="sidebar" class="relative bg-black text-white w-16 hover:w-64 md:w-16 md:hover:w-64 transition-all duration-300 ease-in-out flex-shrink-0 flex flex-col group overflow-hidden md:block fixed md:relative z-40 h-full -translate-x-full md:translate-x-0">
            <!-- Logo -->
            <div class="p-4 flex items-center justify-center group-hover:justify-start group-hover:px-6 transition-all duration-300">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span class="text-xl font-semibold ml-3 opacity-0 group-hover:opacity-100 transition-all duration-300 whitespace-nowrap">Skala</span>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 px-2 pb-4 sidebar-scroll overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }} group-hover:justify-start justify-center" title="Dashboard">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium ml-3 opacity-0 group-hover:opacity-100 transition-all duration-300 whitespace-nowrap">Dashboard</span>
                </a>

                <!-- Desempenho do Time -->
                <a href="{{ route('desempenho-time') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('desempenho-time') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }} group-hover:justify-start justify-center" title="Desempenho do Time">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="font-medium ml-3 opacity-0 group-hover:opacity-100 transition-all duration-300 whitespace-nowrap">Desempenho do Time</span>
                </a>

                @php
                    $colaboradorKanbanSetor = session('colaborador')->setor->nome ?? '';
                @endphp
                @if($colaboradorKanbanSetor === 'Vendas' || $colaboradorKanbanSetor === 'Administrativo')
                <!-- Kanban de Vendas -->
                <a href="{{ route('kanban-vendas.index') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('kanban-vendas.*') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                    </svg>
                    <span class="font-medium">Kanban de Vendas</span>
                </a>
                @endif
                
                <!-- Reuniões -->
                <a href="{{ route('reunioes.index') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('reunioes.*') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="font-medium">Reuniões</span>
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

                @php
                    $colaboradorSetor = session('colaborador')->setor->nome ?? '';
                    $colaboradorSetorId = session('colaborador')->setor_id ?? 0;
                @endphp

                @if($colaboradorSetor === 'Administrativo' || $colaboradorSetorId === 3)
                    <!-- Administrativo Section -->
                    <div class="mt-6 pt-6 border-t border-gray-800">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Administrativo</h3>
                        
                        <!-- WhatsApp Instances -->
                        <a href="{{ route('whatsapp-instances.index') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('whatsapp-instances.*') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span class="font-medium">WhatsApp API</span>
                        </a>
                        
                        <!-- WhatsApp Chat -->
                        <a href="{{ route('whatsapp-chat.index') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('whatsapp-chat.*') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <i class="fab fa-whatsapp w-5 h-5 mr-3 text-green-400"></i>
                            <span class="font-medium">WhatsApp Chat</span>
                        </a>
                    </div>
                @endif

                @if($colaboradorSetor === 'Administrativo' || $colaboradorSetorId === 3 || $colaboradorSetor === 'Financeiro')
                    <!-- Financeiro Section -->
                    <div class="mt-6 pt-6 border-t border-gray-800">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Financeiro</h3>
                        
                        <!-- Dashboard Financeira -->
                        <a href="{{ route('financeiro.dashboard') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.dashboard') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium">Dashboard Financeira</span>
                        </a>

                        <!-- Fluxo de Caixa -->
                        <a href="{{ route('financeiro.fluxo-caixa') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.fluxo-caixa') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium">Fluxo de Caixa</span>
                        </a>

                        <!-- Tipos de Custo -->
                        <a href="{{ route('financeiro.tipos-custo') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.tipos-custo') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="font-medium">Tipos de Custo</span>
                        </a>

                        <!-- Categorias -->
                        <a href="{{ route('financeiro.categorias') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.categorias') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="font-medium">Categorias</span>
                        </a>

                        <!-- Contas Bancárias -->
                        <a href="{{ route('financeiro.contas-bancarias') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.contas-bancarias') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <span class="font-medium">Contas Bancárias</span>
                        </a>

                        <!-- Contas a Pagar -->
                        <a href="{{ route('financeiro.contas-pagar') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.contas-pagar') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">Contas a Pagar</span>
                        </a>

                        <!-- Contas a Receber -->
                        <a href="{{ route('financeiro.contas-receber') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.contas-receber') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">Contas a Receber</span>
                        </a>

                        <!-- Fornecedores -->
                        <a href="{{ route('financeiro.fornecedores') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.fornecedores') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium">Fornecedores</span>
                        </a>

                        <!-- Importação OFX -->
                        <a href="{{ route('financeiro.importacao-ofx') }}" class="flex items-center px-3 py-2 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('financeiro.importacao-ofx') ? 'bg-white text-black' : 'hover:bg-gray-900 text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span class="font-medium">Importação OFX</span>
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
            <header class="header-dark shadow-sm border-b">
                <div class="px-6 py-4">
                    <h1 class="text-2xl font-semibold text-primary-dark">@yield('title', 'Dashboard')</h1>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto main-content md:ml-0 ml-0">
                <div class="px-6 py-6">
                    @if(session('success'))
                        <div class="mb-4 alert-success-dark px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 alert-error-dark px-4 py-3 rounded">
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
            
            // Converter todos os links da sidebar para o formato responsivo
            const sidebar = document.getElementById('sidebar');
            const navLinks = sidebar.querySelectorAll('nav a:not([data-converted])');
            
            navLinks.forEach(link => {
                // Adicionar classes para responsividade
                if (!link.classList.contains('group-hover:justify-start')) {
                    link.classList.add('group-hover:justify-start', 'justify-center');
                }
                
                // Encontrar o SVG
                const svg = link.querySelector('svg');
                if (svg) {
                    svg.classList.remove('mr-3', 'mr-2');
                    svg.classList.add('flex-shrink-0');
                }
                
                // Encontrar o span de texto
                const textSpan = link.querySelector('span:not(.bg-green-500):not(.bg-red-500):not(.bg-yellow-500)');
                if (textSpan && !textSpan.classList.contains('opacity-0')) {
                    textSpan.classList.add('ml-3', 'opacity-0', 'group-hover:opacity-100', 'transition-all', 'duration-300', 'whitespace-nowrap');
                }
                
                // Adicionar title para tooltip
                if (!link.hasAttribute('title') && textSpan) {
                    link.setAttribute('title', textSpan.textContent.trim());
                }
                
                // Marcar como convertido
                link.setAttribute('data-converted', 'true');
            });
            
            // Converter botões também (tratamento especial para dropdowns)
            const navButtons = sidebar.querySelectorAll('nav button:not([data-converted])');
            navButtons.forEach(button => {
                // Verificar se é um botão de dropdown (tem justify-between)
                const isDropdown = button.classList.contains('justify-between');
                
                if (isDropdown) {
                    // Tratamento especial para dropdowns
                    const iconDiv = button.querySelector('div.flex.items-center');
                    const chevron = button.querySelector('svg.w-4.h-4');
                    
                    if (iconDiv) {
                        // Remover justify-between e adicionar justify-center com hover
                        button.classList.remove('justify-between');
                        button.classList.add('justify-center', 'group-hover:justify-between');
                        
                        // Ajustar o div interno
                        iconDiv.classList.add('group-hover:flex', 'group-hover:items-center');
                        
                        // Ajustar o ícone principal
                        const mainIcon = iconDiv.querySelector('svg');
                        if (mainIcon) {
                            mainIcon.classList.remove('mr-3');
                            mainIcon.classList.add('group-hover:mr-3');
                        }
                        
                        // Ajustar o texto
                        const textSpan = iconDiv.querySelector('span');
                        if (textSpan) {
                            textSpan.classList.add('hidden', 'group-hover:inline', 'transition-all', 'duration-300');
                        }
                        
                        // Esconder o chevron quando colapsado
                        if (chevron) {
                            chevron.classList.add('hidden', 'group-hover:block');
                        }
                        
                        // Adicionar título para tooltip
                        if (textSpan && !button.hasAttribute('title')) {
                            button.setAttribute('title', textSpan.textContent.trim());
                        }
                    }
                } else {
                    // Tratamento normal para botões não-dropdown
                    if (!button.classList.contains('group-hover:justify-start')) {
                        button.classList.add('group-hover:justify-start', 'justify-center');
                    }
                    
                    const svg = button.querySelector('svg');
                    if (svg && !svg.classList.contains('transition-transform')) {
                        svg.classList.remove('mr-3', 'mr-2');
                        svg.classList.add('flex-shrink-0');
                    }
                    
                    const textSpan = button.querySelector('span:not(svg)');
                    if (textSpan && !textSpan.classList.contains('opacity-0')) {
                        textSpan.classList.add('ml-3', 'opacity-0', 'group-hover:opacity-100', 'transition-all', 'duration-300', 'whitespace-nowrap');
                    }
                    
                    if (!button.hasAttribute('title') && textSpan) {
                        button.setAttribute('title', textSpan.textContent.trim());
                    }
                }
                
                button.setAttribute('data-converted', 'true');
            });
            
            // Converter headers de seção
            const sectionHeaders = sidebar.querySelectorAll('h3');
            sectionHeaders.forEach(header => {
                if (!header.classList.contains('opacity-0')) {
                    header.classList.add('opacity-0', 'group-hover:opacity-100', 'transition-all', 'duration-300', 'whitespace-nowrap');
                }
            });
            
            // Toggle sidebar mobile
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarElement = document.getElementById('sidebar');
            
            if (sidebarToggle && sidebarElement) {
                sidebarToggle.addEventListener('click', function() {
                    sidebarElement.classList.toggle('-translate-x-full');
                });
                
                // Fechar sidebar quando clicar fora (mobile)
                document.addEventListener('click', function(e) {
                    if (window.innerWidth < 768 && 
                        !sidebarElement.contains(e.target) && 
                        !sidebarToggle.contains(e.target) &&
                        !sidebarElement.classList.contains('-translate-x-full')) {
                        sidebarElement.classList.add('-translate-x-full');
                    }
                });
            }
        });
    </script>
</body>
</html>