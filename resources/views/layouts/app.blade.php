<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SATAS Bus') }}</title>

        <!-- Fonts & Icons -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900">
        <div class="min-h-screen bg-gray-50 flex flex-col">
            
            <!-- Navbar -->
            <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-satas-red-gradient flex items-center justify-center text-white">
                                    <i class="fa-solid fa-bus-simple text-sm"></i>
                                </div>
                                <span class="text-xl font-black text-gray-900 tracking-tight">SATAS</span>
                            </a>
                        </div>
                        
                        <div class="hidden md:flex items-center space-x-6">
                            <a href="{{ route('home') }}" class="nav-link">Accueil</a>
                            <a href="{{ route('search.index') }}" class="nav-link">Rechercher</a>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            @auth
                                <div class="relative flex items-center gap-3">
                                    <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ Auth::user()->name }}</span>
                                    
                                    <div class="flex items-center gap-2 border-l pl-4 ml-2 border-gray-200">
                                        @if(Auth::user()->role === 'admin')
                                            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-satas-red font-medium">Admin</a>
                                        @else
                                            <a href="{{ route('bookings.index') }}" class="text-sm text-gray-600 hover:text-satas-red font-medium">Mes Billets</a>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('logout') }}" class="ml-2">
                                            @csrf
                                            <button type="submit" class="text-gray-400 hover:text-red-500" title="Déconnexion">
                                                <i class="fa-solid fa-right-from-bracket"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">Connexion</a>
                                <a href="{{ route('register') }}" class="btn-primary py-1.5 px-4 text-sm">S'inscrire</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-grow">
                @yield('content')
                {{ $slot ?? '' }}
            </main>
            
            <!-- Footer Simple -->
            <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-gray-500">&copy; {{ date('Y') }} SATAS Bus. Tous droits réservés.</p>
                    <div class="flex space-x-4 mt-4 md:mt-0 text-sm">
                        <a href="#" class="text-gray-400 hover:text-gray-900">Conditions</a>
                        <a href="#" class="text-gray-400 hover:text-gray-900">Confidentialité</a>
                        <a href="#" class="text-gray-400 hover:text-gray-900">Contact</a>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
