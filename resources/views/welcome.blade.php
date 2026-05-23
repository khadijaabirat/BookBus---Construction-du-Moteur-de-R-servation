<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SATAS Bus - Voyagez dans tout le Maroc avec confort et sécurité</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">

    <!-- Navigation -->
    <nav class="navbar transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-xl bg-satas-red-gradient flex items-center justify-center text-white shadow-glow">
                            <i class="fa-solid fa-bus-simple text-xl"></i>
                        </div>
                        <span class="text-2xl font-black text-gray-900 tracking-tight">SATAS <span class="text-red-600">Bus</span></span>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="nav-link active">Accueil</a>
                    <a href="{{ route('search.index') }}" class="nav-link">Rechercher</a>
                    <a href="#destinations" class="nav-link">Destinations</a>
                    <a href="#services" class="nav-link">Nos Services</a>
                </div>
                
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn-ghost">Dashboard</a>
                        @else
                            <a href="{{ route('bookings.index') }}" class="btn-ghost">Mes Réservations</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="btn-outline px-4 py-2 text-sm rounded-lg">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost font-semibold">Connexion</a>
                        <a href="{{ route('register') }}" class="btn-primary px-5 py-2 text-sm rounded-lg">S'inscrire</a>
                    @endauth
                </div>
                
                <div class="flex items-center md:hidden">
                    <button class="text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-satas-hero overflow-hidden">
        <!-- Abstract Background Shapes -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[20%] -right-[10%] w-[50%] h-[100%] rounded-full bg-gradient-to-br from-red-500/20 to-orange-500/10 blur-3xl animate-pulse-slow"></div>
            <div class="absolute -bottom-[20%] -left-[10%] w-[60%] h-[80%] rounded-full bg-gradient-to-tr from-blue-500/20 to-teal-500/10 blur-3xl animate-float"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-24 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left text-white animate-slide-up">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 backdrop-blur-md mb-6">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                        </span>
                        <span class="text-sm font-medium">Nouveau : Lignes Premium</span>
                    </div>
                    
                    <h1 class="text-5xl lg:text-6xl font-extrabold tracking-tight mb-6 leading-tight">
                        Voyagez avec <span class="text-gradient">confort</span> à travers le Maroc
                    </h1>
                    <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto lg:mx-0">
                        La compagnie SATAS vous accompagne dans tous vos déplacements avec une flotte moderne, des chauffeurs expérimentés et un service de qualité.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#search-form" class="btn-primary btn-lg shadow-glow">
                            Réserver un billet
                        </a>
                        <a href="#services" class="btn btn-lg bg-white/10 text-white hover:bg-white/20 backdrop-blur-md border border-white/20">
                            Découvrir nos services
                        </a>
                    </div>
                </div>

                <!-- Search Form Card -->
                <div id="search-form" class="animate-scale-in" style="animation-delay: 0.2s;">
                    <div class="card-glass p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Où allez-vous ?</h3>
                        
                        <form action="{{ route('search.trip') }}" method="GET">
                            <div class="space-y-5">
                                <div class="form-group relative">
                                    <label class="form-label">Départ</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                            <i class="fa-solid fa-location-dot"></i>
                                        </div>
                                        <select name="departure_city" class="form-select pl-10" required>
                                            <option value="" disabled selected>Ville de départ</option>
                                            @foreach($villes as $ville)
                                                <option value="{{ $ville->id }}">{{ $ville->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="relative flex justify-center -my-3 z-10">
                                    <button type="button" class="w-10 h-10 rounded-full bg-white shadow-md border border-gray-100 flex items-center justify-center text-red-500 hover:bg-red-50 transition-colors">
                                        <i class="fa-solid fa-arrow-right-arrow-left rotate-90"></i>
                                    </button>
                                </div>

                                <div class="form-group relative">
                                    <label class="form-label">Arrivée</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                            <i class="fa-solid fa-map-pin"></i>
                                        </div>
                                        <select name="arrival_city" class="form-select pl-10" required>
                                            <option value="" disabled selected>Ville d'arrivée</option>
                                            @foreach($villes as $ville)
                                                <option value="{{ $ville->id }}">{{ $ville->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group relative">
                                    <label class="form-label">Date du voyage</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                            <i class="fa-solid fa-calendar-day"></i>
                                        </div>
                                        <input type="date" name="travel_date" class="form-input pl-10" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn-primary w-full py-4 text-lg mt-2">
                                    <i class="fa-solid fa-magnifying-glass mr-2"></i> Rechercher les trajets
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Wave shape divider -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
            <svg class="relative block w-full h-[50px] md:h-[100px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118,130.85,130.23,200.5,120.3,243.68,114.1,283.67,91.82,321.39,56.44Z" fill="#F8F9FA"></path>
            </svg>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="py-12 bg-gray-50 -mt-10 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card bg-white grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-100 shadow-lg">
                <div class="stat-card">
                    <div class="w-12 h-12 mx-auto bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center mb-4">
                        <i class="fa-solid fa-city text-2xl"></i>
                    </div>
                    <div class="stat-number">{{ $stats['cities'] }}</div>
                    <div class="stat-label">Villes Desservies</div>
                </div>
                <div class="stat-card">
                    <div class="w-12 h-12 mx-auto bg-red-50 text-red-500 rounded-xl flex items-center justify-center mb-4">
                        <i class="fa-solid fa-route text-2xl"></i>
                    </div>
                    <div class="stat-number">{{ $stats['daily_trips'] }}</div>
                    <div class="stat-label">Trajets par Jour</div>
                </div>
                <div class="stat-card">
                    <div class="w-12 h-12 mx-auto bg-teal-50 text-teal-500 rounded-xl flex items-center justify-center mb-4">
                        <i class="fa-solid fa-face-smile text-2xl"></i>
                    </div>
                    <div class="stat-number">{{ number_format($stats['happy_customers']) }}+</div>
                    <div class="stat-label">Clients Satisfaits</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Destinations Section -->
    <div id="destinations" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-red-500 font-bold tracking-wider uppercase text-sm mb-2 block">Nos Destinations</span>
                <h2 class="section-title">Voyagez partout au Maroc</h2>
                <p class="section-subtitle">Découvrez nos principales destinations à travers le Royaume avec des départs quotidiens.</p>
            </div>

            @php
            $cityImages = [
                // Casablanca - Mosquée Hassan II
                'Casablanca' => 'https://images.unsplash.com/photo-1548013146-72479768bada?w=800&h=400&fit=crop',
                // Marrakech - Jemaa el-Fna
                'Marrakech' => 'https://images.unsplash.com/photo-1539020140153-e479b8c22e70?w=800&h=400&fit=crop',
                // Rabat - Tour Hassan
                'Rabat' => 'https://images.unsplash.com/photo-1577083552431-6e5fd01988ec?w=800&h=400&fit=crop',
                // Fès - Tanneries Chouara
                'Fès' => 'https://images.unsplash.com/photo-1553603227-2358aabe821e?w=800&h=400&fit=crop',
                // Tanger - Médina
                'Tanger' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800&h=400&fit=crop',
                // Agadir - Plage
                'Agadir' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&h=400&fit=crop',
            ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($villes->take(6) as $ville)
                @php
                    $imageSrc = $cityImages[$ville->name] ?? 'https://images.unsplash.com/photo-1548013146-72479768bada?w=800&h=400&fit=crop';
                @endphp
                <div class="card overflow-hidden group hover:shadow-xl transition-all duration-300">
                    <div class="h-48 relative overflow-hidden bg-gray-200">
                        <img src="{{ $imageSrc }}" alt="{{ $ville->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" onerror="this.src='https://via.placeholder.com/800x400/3B82F6/FFFFFF?text={{ urlencode($ville->name) }}'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                        <div class="absolute top-4 right-4">
                            <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <i class="fa-solid fa-bus text-white"></i>
                            </div>
                        </div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <h3 class="text-3xl font-bold text-white drop-shadow-lg">{{ $ville->name }}</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">Plusieurs départs quotidiens vers {{ $ville->name }}</p>
                        <a href="{{ route('search.index') }}" class="text-red-600 font-semibold hover:text-red-700 inline-flex items-center gap-2 group-hover:gap-3 transition-all">
                            Voir les horaires <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('search.index') }}" class="btn-primary inline-flex items-center gap-2">
                    Voir toutes les destinations <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div id="services" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-red-500 font-bold tracking-wider uppercase text-sm mb-2 block">L'Expérience SATAS</span>
                <h2 class="section-title">Pourquoi voyager avec nous ?</h2>
                <p class="section-subtitle">Nous mettons tout en œuvre pour vous offrir un voyage agréable, sûr et ponctuel à travers tout le Royaume.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card p-8 text-center group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-20 h-20 mx-auto bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-bus text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Flotte Premium</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Des bus modernes équipés de sièges inclinables, climatisation individuelle, prises USB et Wi-Fi haut débit.
                    </p>
                </div>
                
                <div class="card p-8 text-center group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-20 h-20 mx-auto bg-teal-50 rounded-2xl flex items-center justify-center text-teal-600 mb-6 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-shield-halved text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Sécurité Absolue</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Chauffeurs professionnels expérimentés, bus régulièrement révisés et suivi GPS en temps réel.
                    </p>
                </div>

                <div class="card p-8 text-center group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-20 h-20 mx-auto bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 mb-6 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-clock text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Ponctualité</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Respect strict des horaires de départ et d'arrivée pour vous permettre de mieux planifier vos journées.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded bg-red-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-bus-simple text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-white">SATAS</span>
                    </div>
                    <p class="text-sm text-gray-400 mb-4">Votre partenaire de confiance pour vos voyages en autocar à travers le Maroc depuis plus de 20 ans.</p>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-4 uppercase tracking-wider text-sm">Navigation</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Accueil</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Destinations</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Horaires</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Agences</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-4 uppercase tracking-wider text-sm">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contactez-nous</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Conditions Générales</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Politique de confidentialité</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-4 uppercase tracking-wider text-sm">Contact</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-location-dot mt-1 text-red-500"></i>
                            <span>Gare Routière Oulad Ziane<br>Casablanca, Maroc</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-phone text-red-500"></i>
                            <span>+212 5 22 00 00 00</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-envelope text-red-500"></i>
                            <span>contact@satas.ma</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center text-sm">
                <p>&copy; {{ date('Y') }} SATAS Bus. Tous droits réservés.</p>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
