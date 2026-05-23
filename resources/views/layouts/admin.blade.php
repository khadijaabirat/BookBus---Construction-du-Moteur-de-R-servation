<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - SATAS Bus</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .sidebar { background-color: #1D3557; color: white; transition: all 0.3s ease; }
        .sidebar-link { display: flex; items-center; padding: 12px 20px; color: #a0aec0; transition: all 0.3s; }
        .sidebar-link:hover, .sidebar-link.active { background-color: rgba(255,255,255,0.1); color: white; border-left: 4px solid #E63946; }
        .sidebar-icon { width: 24px; text-align: center; margin-right: 12px; }
        .topbar { background: white; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="sidebar w-64 flex flex-col hidden md:flex h-full">
        <div class="h-16 flex items-center px-6 border-b border-gray-700">
            <span class="text-xl font-bold text-white tracking-wider"><i class="fa-solid fa-bus text-red-500 mr-2"></i>SATAS Admin</span>
        </div>
        
        <nav class="flex-1 overflow-y-auto py-4">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge sidebar-icon"></i> Dashboard
            </a>
            
            <div class="px-6 py-2 mt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Flotte & Trajets</div>
            
            <a href="{{ route('admin.buses.index') }}" class="sidebar-link {{ request()->routeIs('admin.buses.*') ? 'active' : '' }}">
                <i class="fa-solid fa-bus-simple sidebar-icon"></i> Bus
            </a>
            <a href="{{ route('admin.routes.index') }}" class="sidebar-link {{ request()->routeIs('admin.routes.*') ? 'active' : '' }}">
                <i class="fa-solid fa-route sidebar-icon"></i> Lignes & Trajets
            </a>
            <a href="{{ route('admin.trips.index') }}" class="sidebar-link {{ request()->routeIs('admin.trips.*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-alt sidebar-icon"></i> Programmes
            </a>
            
            <div class="px-6 py-2 mt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Gestion</div>
            
            <a href="{{ route('admin.employees.index') }}" class="sidebar-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users-gear sidebar-icon"></i> Employés
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="sidebar-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-ticket sidebar-icon"></i> Réservations
            </a>
        </nav>
        
        <div class="p-4 border-t border-gray-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full sidebar-link rounded-lg bg-red-600 hover:bg-red-700 text-white !border-0 justify-center">
                    <i class="fa-solid fa-right-from-bracket sidebar-icon"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Topbar -->
        <header class="topbar h-16 flex items-center justify-between px-6 z-10 relative">
            <div class="flex items-center">
                <button class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h1 class="text-xl font-semibold text-gray-800 ml-4 hidden sm:block">@yield('title')</h1>
            </div>
            
            <div class="flex items-center">
                <div class="relative flex items-center gap-3">
                    <div class="text-sm text-right hidden sm:block">
                        <div class="font-medium text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</div>
                        <div class="text-xs text-gray-500">Administrateur</div>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Page Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle mr-3"></i>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <div class="flex items-center">
                        <i class="fa-solid fa-circle-exclamation mr-3"></i>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
