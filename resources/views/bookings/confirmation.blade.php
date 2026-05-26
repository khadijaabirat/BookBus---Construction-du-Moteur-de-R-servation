@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-8">
            @php $firstBooking = $reservations->first(); $isEnAttente = $firstBooking && $firstBooking->statut === 'En attente'; @endphp
            @if($isEnAttente)
                <div class="w-20 h-20 mx-auto bg-orange-100 rounded-full flex items-center justify-center text-orange-500 text-4xl mb-4">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Réservation en attente</h1>
                <p class="text-gray-600">Votre réservation a été enregistrée. Elle sera <strong>confirmée par l'administrateur</strong> après validation de votre paiement.</p>
                <div class="mt-4 bg-orange-50 border border-orange-200 rounded-xl p-4 text-sm text-orange-800 max-w-lg mx-auto">
                    <i class="fa-solid fa-circle-info mr-2"></i>
                    @if($firstBooking->payment_method === 'cash')
                        Présentez-vous à la gare SATAS avec votre référence de réservation pour effectuer le paiement en espèces.
                    @else
                        Votre justificatif de virement a été reçu. L'administrateur validera votre réservation dans les plus brefs délais.
                    @endif
                </div>
            @else
                <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center text-green-500 text-4xl mb-4 animate-scale-in">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Réservation Confirmée !</h1>
                <p class="text-gray-600">Votre paiement a été traité avec succès. Préparez-vous pour un agréable voyage avec SATAS.</p>
            @endif
        </div>

        <div class="space-y-6">
            @foreach($reservations as $booking)
                <div class="card overflow-hidden">
                    <!-- Ticket Header -->
                    <div class="bg-satas-navy text-white p-6 relative {{ $booking->statut === 'En attente' ? 'bg-orange-800' : '' }}" style="{{ $booking->statut === 'En attente' ? 'background:#92400e' : '' }}">
                        <!-- Ticket Cutouts -->
                        <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-gray-50 rounded-full"></div>
                        <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-gray-50 rounded-full"></div>
                        
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-white/60 text-sm uppercase tracking-wider font-semibold">Billet SATAS</span>
                                <h3 class="text-2xl font-bold font-mono mt-1">{{ $booking->reference }}</h3>
                                @if($booking->statut === 'En attente')
                                    <span class="text-xs bg-orange-200 text-orange-900 px-2 py-0.5 rounded-full mt-1 inline-block">
                                        <i class="fa-solid fa-clock mr-1"></i>En attente de validation
                                    </span>
                                @endif
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
                        @if($booking->statut === 'Confirmé')
                            <a href="{{ route('bookings.download', $booking->id) }}" class="text-satas-red hover:underline font-medium">
                                <i class="fa-solid fa-download mr-1"></i> Télécharger PDF
                            </a>
                        @else
                            <span class="text-orange-500 text-xs font-medium">
                                <i class="fa-solid fa-clock mr-1"></i> PDF disponible après validation
                            </span>
                        @endif
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
