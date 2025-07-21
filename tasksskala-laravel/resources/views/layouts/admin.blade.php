<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .sidebar-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .nav-item {
            transition: all 0.3s ease;
        }
        .nav-item:hover {
            transform: translateX(8px);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        .nav-item.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid #ffffff;
            transform: translateX(4px);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Modern Sidebar -->
        <div class="sidebar-gradient text-white w-72 space-y-6 py-8 px-6 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-300 ease-in-out shadow-2xl">
            <!-- Logo/Brand -->
            <div class="text-center">
                <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center space-y-3 group">
                    <div class="w-16 h-16 glass-effect rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-crown text-2xl text-yellow-300"></i>
                    </div>
                    <div>
                        <span class="text-2xl font-bold bg-gradient-to-r from-white to-yellow-200 bg-clip-text text-transparent">SkalaCode</span>
                        <p class="text-sm text-purple-200 font-medium">Admin Panel</p>
                    </div>
                </a>
            </div>
            
            <!-- Admin Profile -->
            <div class="glass-effect rounded-xl p-4 text-center">
                <div class="w-12 h-12 mx-auto bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full flex items-center justify-center mb-3">
                    <i class="fas fa-user-shield text-white"></i>
                </div>
                <p class="text-sm font-medium">Administrador</p>
                <p class="text-xs text-purple-200">Sistema</p>
            </div>
            
            <!-- Navigation -->
            <nav class="space-y-2">
                <div class="text-xs uppercase text-purple-200 font-semibold tracking-wider mb-4 px-3">
                    Menu Principal
                </div>
                
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center space-x-4 py-3 px-4 rounded-xl">
                    <i class="fas fa-chart-pie w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="{{ route('admin.colaboradores.index') }}" class="nav-item {{ request()->routeIs('admin.colaboradores.*') ? 'active' : '' }} flex items-center space-x-4 py-3 px-4 rounded-xl">
                    <i class="fas fa-users w-5"></i>
                    <span class="font-medium">Colaboradores</span>
                </a>
                
                <a href="{{ route('admin.clientes.index') }}" class="nav-item {{ request()->routeIs('admin.clientes.*') ? 'active' : '' }} flex items-center space-x-4 py-3 px-4 rounded-xl">
                    <i class="fas fa-handshake w-5"></i>
                    <span class="font-medium">Clientes</span>
                </a>
                
                <a href="{{ route('admin.projetos.index') }}" class="nav-item {{ request()->routeIs('admin.projetos.*') ? 'active' : '' }} flex items-center space-x-4 py-3 px-4 rounded-xl">
                    <i class="fas fa-project-diagram w-5"></i>
                    <span class="font-medium">Projetos</span>
                </a>
                
                <a href="{{ route('admin.tarefas.index') }}" class="nav-item {{ request()->routeIs('admin.tarefas.*') ? 'active' : '' }} flex items-center space-x-4 py-3 px-4 rounded-xl">
                    <i class="fas fa-tasks w-5"></i>
                    <span class="font-medium">Tarefas</span>
                </a>
                
                <!-- Configurações Seção -->
                <div class="pt-6">
                    <div class="text-xs uppercase text-purple-200 font-semibold tracking-wider mb-4 px-3">
                        Configurações
                    </div>
                    
                    <a href="{{ route('admin.setores.index') }}" class="nav-item {{ request()->routeIs('admin.setores.*') ? 'active' : '' }} flex items-center space-x-4 py-3 px-4 rounded-xl">
                        <i class="fas fa-building w-5"></i>
                        <span class="font-medium">Setores</span>
                    </a>
                    
                    <a href="{{ route('admin.conhecimentos.index') }}" class="nav-item {{ request()->routeIs('admin.conhecimentos.*') ? 'active' : '' }} flex items-center space-x-4 py-3 px-4 rounded-xl">
                        <i class="fas fa-lightbulb w-5"></i>
                        <span class="font-medium">Conhecimentos</span>
                    </a>
                    
                    <a href="{{ route('admin.status-projetos.index') }}" class="nav-item {{ request()->routeIs('admin.status-projetos.*') ? 'active' : '' }} flex items-center space-x-4 py-3 px-4 rounded-xl">
                        <i class="fas fa-flag w-5"></i>
                        <span class="font-medium">Status</span>
                    </a>
                    
                    <a href="{{ route('admin.agente-skala.index') }}" class="nav-item {{ request()->routeIs('admin.agente-skala.*') ? 'active' : '' }} flex items-center space-x-4 py-3 px-4 rounded-xl">
                        <i class="fas fa-robot w-5"></i>
                        <span class="font-medium">Agente Skala</span>
                    </a>
                    
                    <a href="{{ route('admin.tutoriais.index') }}" class="nav-item {{ request()->routeIs('admin.tutoriais.*') ? 'active' : '' }} flex items-center space-x-4 py-3 px-4 rounded-xl">
                        <i class="fas fa-video w-5"></i>
                        <span class="font-medium">Tutoriais</span>
                    </a>
                    
                    <a href="{{ route('admin.feedbacks.index') }}" class="nav-item {{ request()->routeIs('admin.feedbacks.*') ? 'active' : '' }} flex items-center space-x-4 py-3 px-4 rounded-xl">
                        <i class="fas fa-comments w-5"></i>
                        <span class="font-medium">Feedbacks</span>
                    </a>
                    <div class="absolute bottom-6 left-6 right-6">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full nav-item flex items-center space-x-4 py-3 px-4 rounded-xl text-red-200 hover:text-white hover:bg-red-500/20">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="font-medium">Sair</span>
                    </button>
                </form>
            </div>
                </div>
                 <!-- Logout Button -->
            
            </nav>
            
           
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top navigation -->
            <header class="bg-white shadow-lg">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <h1 class="text-3xl font-bold text-gray-900">@yield('title', 'Dashboard')</h1>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Ver Site</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>