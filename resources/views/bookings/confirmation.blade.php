@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-8">
            <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center text-green-500 text-4xl mb-4 animate-scale-in">
                <i class="fa-solid fa-check"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Réservation Confirmée !</h1>
            <p class="text-gray-600">Votre paiement a été traité avec succès. Préparez-vous pour un agréable voyage avec SATAS.</p>
        </div>

        <div class="space-y-6">
            @foreach($reservations as $booking)
                <div class="card overflow-hidden">
                    <!-- Ticket Header -->
                    <div class="bg-satas-navy text-white p-6 relative">
                        <!-- Ticket Cutouts -->
                        <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-gray-50 rounded-full"></div>
                        <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-gray-50 rounded-full"></div>
                        
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-white/60 text-sm uppercase tracking-wider font-semibold">Billet SATAS</span>
                                <h3 class="text-2xl font-bold font-mono mt-1">{{ $booking->reference }}</h3>
                            </div>
                            <div class="text-right">
                                <i class="fa-solid fa-qrcode text-4xl opacity-80"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ticket Body -->
                    <div class="p-6 bg-white border-b border-dashed border-gray-300">
                        <div class="flex items-center justify-between mb-8">
                            <div class="text-center w-1/3">
                                <div class="text-3xl font-black text-gray-900">{{ \Carbon\Carbon::parse($booking->segment->programme->heure_depart)->format('H:i') }}</div>
                                <div class="text-sm font-semibold text-gray-500 uppercase">{{ $booking->segment->depart->gare->ville->name }}</div>
                            </div>
                            <div class="flex-1 flex flex-col items-center justify-center px-4">
                                <span class="text-xs text-gray-400 mb-1 font-medium">{{ \Carbon\Carbon::parse($booking->segment->programme->jour_depart)->format('d/m/Y') }}</span>
                                <div class="w-full h-px bg-gray-300 relative flex items-center justify-center">
                                    <i class="fa-solid fa-bus absolute bg-white px-2 text-gray-400"></i>
                                </div>
                            </div>
                            <div class="text-center w-1/3">
                                <div class="text-3xl font-black text-gray-900">{{ \Carbon\Carbon::parse($booking->segment->programme->heure_arrivee)->format('H:i') }}</div>
                                <div class="text-sm font-semibold text-gray-500 uppercase">{{ $booking->segment->arrivee->gare->ville->name }}</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div>
                                <span class="text-xs text-gray-500 block mb-1">Passager</span>
                                <span class="font-semibold text-gray-900">{{ $booking->user->name }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block mb-1">Siège</span>
                                <span class="font-bold text-lg text-satas-red">{{ $booking->siege_numero }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block mb-1">Bus</span>
                                <span class="font-semibold text-gray-900">{{ $booking->segment->bus->type }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block mb-1">Prix Total</span>
                                <span class="font-bold text-gray-900">{{ number_format($booking->total_price, 2) }} MAD</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ticket Footer -->
                    <div class="p-4 bg-gray-50 flex justify-between items-center text-sm">
                        <div class="flex gap-4">
                            @if($booking->snack_box)
                                <span class="text-green-600 font-medium flex items-center gap-1"><i class="fa-solid fa-box"></i> Snack-box</span>
                            @endif
                            @if($booking->insurance)
                                <span class="text-blue-600 font-medium flex items-center gap-1"><i class="fa-solid fa-shield"></i> Assurance</span>
                            @endif
                        </div>
                        <a href="{{ route('bookings.download', $booking->id) }}" class="text-satas-red hover:underline font-medium"><i class="fa-solid fa-download mr-1"></i> Télécharger PDF</a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-8 text-center">
            <a href="{{ route('bookings.index') }}" class="btn-primary">
                Voir toutes mes réservations
            </a>
            <a href="{{ route('home') }}" class="btn-ghost ml-4">
                Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection
