@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mes Réservations</h1>
            <a href="{{ route('search.index') }}" class="btn-primary">Nouveau Voyage</a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fa-solid fa-check text-green-500 mr-3"></i>
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 mr-3"></i>
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($bookings->isEmpty())
            <div class="card p-12 text-center border-dashed border-2 border-gray-200">
                <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center text-gray-400 text-3xl mb-4">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Aucune réservation</h3>
                <p class="text-gray-500 mb-6">Vous n'avez pas encore effectué de réservation avec SATAS.</p>
                <a href="{{ route('search.index') }}" class="btn-primary inline-flex">Rechercher un billet</a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($bookings as $booking)
                    @php
                        $jourDepart = $booking->segment->programme->jour_depart;
                        $heureDepart = $booking->segment->programme->heure_depart;
                        
                        // Handle if jour_depart already contains time
                        if (strpos($jourDepart, ' ') !== false) {
                            $departTime = \Carbon\Carbon::parse($jourDepart);
                        } else {
                            $departTime = \Carbon\Carbon::parse($jourDepart . ' ' . $heureDepart);
                        }
                        
                        $isPast = now()->isAfter($departTime);
                        $canCancel = !$isPast && $booking->statut !== 'Annulé';
                    @endphp
                    
                    <div class="card overflow-hidden {{ $booking->statut === 'Annulé' ? 'opacity-75 grayscale' : '' }}">
                        <!-- Header -->
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center {{ $isPast && $booking->statut !== 'Annulé' ? 'bg-gray-100' : 'bg-gray-50' }}">
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-sm font-bold text-gray-600 bg-white px-2 py-1 rounded border border-gray-200">
                                    {{ $booking->reference }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    Réservé le {{ $booking->date_reservation->format('d/m/Y') }}
                                </span>
                            </div>
                            <div>
                                @if($booking->statut === 'Confirmé')
                                    @if($isPast)
                                        <span class="badge bg-gray-200 text-gray-700"><i class="fa-solid fa-check-double mr-1"></i> Terminé</span>
                                    @else
                                        <span class="badge bg-green-100 text-green-800"><i class="fa-solid fa-check mr-1"></i> Confirmé</span>
                                    @endif
                                @elseif($booking->statut === 'Annulé')
                                    <span class="badge bg-red-100 text-red-800"><i class="fa-solid fa-xmark mr-1"></i> Annulé</span>
                                @endif
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="p-6 flex flex-col md:flex-row gap-6 items-center">
                            
                            <!-- Route -->
                            <div class="flex-1 w-full md:w-auto">
                                <div class="text-sm font-semibold text-gray-500 mb-2">
                                    {{ $departTime->isoFormat('dddd D MMMM YYYY') }}
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->segment->programme->heure_depart)->format('H:i') }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->segment->depart->gare->ville->name }}</div>
                                    </div>
                                    <div class="flex-1 px-4 flex items-center justify-center text-gray-300">
                                        <div class="w-full h-px bg-gray-200 relative flex items-center justify-center">
                                            <i class="fa-solid fa-bus absolute bg-white px-2 text-satas-red"></i>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->segment->programme->heure_arrivee)->format('H:i') }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->segment->arrivee->gare->ville->name }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="w-full md:w-48 bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="mb-2">
                                    <span class="text-xs text-gray-500 block">Siège</span>
                                    <span class="font-bold text-lg text-satas-navy">{{ $booking->siege_numero }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Total</span>
                                    <span class="font-bold text-lg text-satas-red">{{ number_format($booking->total_price, 2) }} MAD</span>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="w-full md:w-auto flex flex-row md:flex-col gap-2">
                                <a href="{{ route('bookings.show', $booking->id) }}" class="btn-outline flex-1 md:flex-none text-center">
                                    Détails
                                </a>
                                @if($canCancel)
                                    <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="flex-1 md:flex-none" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce billet ? Les frais d\'annulation s\'appliqueront selon les conditions générales.');">
                                        @csrf
                                        <button type="submit" class="btn w-full text-red-600 hover:bg-red-50 border border-red-200 bg-white">
                                            Annuler
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
