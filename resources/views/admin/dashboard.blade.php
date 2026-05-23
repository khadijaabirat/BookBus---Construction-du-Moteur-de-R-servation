@extends('layouts.admin')

@section('title', 'Tableau de Bord')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Revenue Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Revenus (Ce mois)</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ number_format($stats['revenue_month'], 2) }} <span class="text-sm font-normal text-gray-500">MAD</span></h3>
        </div>
        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
            <i class="fa-solid fa-money-bill-wave text-xl"></i>
        </div>
    </div>

    <!-- Bookings Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Réservations Totales</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_bookings'] }}</h3>
        </div>
        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
            <i class="fa-solid fa-ticket text-xl"></i>
        </div>
    </div>

    <!-- Active Trips Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Trajets Aujourd'hui</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['active_trips_today'] }}</h3>
        </div>
        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
            <i class="fa-solid fa-route text-xl"></i>
        </div>
    </div>

    <!-- Fleet Status Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Flotte Disponible</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['buses_available'] }} <span class="text-sm font-normal text-gray-500">/ {{ $stats['buses_total'] }}</span></h3>
        </div>
        <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
            <i class="fa-solid fa-bus text-xl"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Bookings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800">Réservations Récentes</h2>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium">Voir tout</a>
        </div>
        <div class="p-6">
            @if($recentBookings->isEmpty())
                <p class="text-gray-500 text-center py-4">Aucune réservation récente.</p>
            @else
                <div class="space-y-4">
                    @foreach($recentBookings as $booking)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold">
                                {{ substr($booking->user->name ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $booking->user->name ?? 'Client Inconnu' }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->segment->depart->gare->ville->name }} &rarr; {{ $booking->segment->arrivee->gare->ville->name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-800">{{ number_format($booking->total_price, 2) }} MAD</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Confirmé
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Upcoming Trips -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800">Prochains Départs</h2>
            <a href="{{ route('admin.trips.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium">Voir tout</a>
        </div>
        <div class="p-6">
            @if($upcomingTrips->isEmpty())
                <p class="text-gray-500 text-center py-4">Aucun départ prévu prochainement.</p>
            @else
                <div class="space-y-4">
                    @foreach($upcomingTrips as $trip)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border-l-4 border-indigo-500">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $trip->route->nom }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($trip->jour_depart)->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($trip->heure_depart)->format('H:i') }}</p>
                        </div>
                        <div class="text-right flex items-center gap-3">
                            <div class="text-xs text-gray-500 text-right">
                                @if($trip->assignments->isNotEmpty())
                                    <div><i class="fa-solid fa-bus text-gray-400"></i> {{ $trip->assignments->first()->bus->matricule }}</div>
                                    <div><i class="fa-solid fa-user text-gray-400"></i> {{ $trip->assignments->first()->employee->first_name }}</div>
                                @else
                                    <span class="text-red-500"><i class="fa-solid fa-triangle-exclamation"></i> Non assigné</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
