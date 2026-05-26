@extends('layouts.admin')
@section('title', 'Gestion des Réservations')
@section('content')

@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded text-green-700 text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded text-red-700 text-sm">{{ session('error') }}</div>
@endif

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-800">Toutes les Réservations</h2>
    {{-- Filter tabs --}}
    <div class="flex gap-2 text-sm">
        <a href="{{ request()->fullUrlWithQuery(['statut' => '']) }}"
            class="px-3 py-1.5 rounded-lg {{ !request('statut') ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600' }}">
            Toutes
        </a>
        <a href="{{ request()->fullUrlWithQuery(['statut' => 'En attente']) }}"
            class="px-3 py-1.5 rounded-lg {{ request('statut') === 'En attente' ? 'bg-orange-500 text-white' : 'bg-orange-50 text-orange-600' }}">
            En attente
            @php $pending = \App\Models\Reservation::where('statut','En attente')->count(); @endphp
            @if($pending > 0)
                <span class="ml-1 bg-orange-600 text-white text-xs rounded-full px-1.5">{{ $pending }}</span>
            @endif
        </a>
        <a href="{{ request()->fullUrlWithQuery(['statut' => 'Confirmé']) }}"
            class="px-3 py-1.5 rounded-lg {{ request('statut') === 'Confirmé' ? 'bg-green-600 text-white' : 'bg-green-50 text-green-600' }}">
            Confirmées
        </a>
        <a href="{{ request()->fullUrlWithQuery(['statut' => 'Annulé']) }}"
            class="px-3 py-1.5 rounded-lg {{ request('statut') === 'Annulé' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-600' }}">
            Annulées
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 border-b text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-6 py-4">Référence</th>
                <th class="px-6 py-4">Client</th>
                <th class="px-6 py-4">Trajet</th>
                <th class="px-6 py-4">Paiement</th>
                <th class="px-6 py-4">Justificatif</th>
                <th class="px-6 py-4">Prix</th>
                <th class="px-6 py-4">Statut</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($bookings as $booking)
            <tr class="hover:bg-gray-50 {{ $booking->statut === 'En attente' ? 'bg-orange-50/40' : '' }}">
                <td class="px-6 py-4 font-mono text-xs font-bold">{{ $booking->reference }}</td>
                <td class="px-6 py-4">{{ $booking->user->name ?? 'N/A' }}</td>
                <td class="px-6 py-4 text-xs">
                    {{ $booking->segment->depart->gare->ville->name }}
                    <i class="fa-solid fa-arrow-right mx-1 text-gray-400"></i>
                    {{ $booking->segment->arrivee->gare->ville->name }}
                </td>
                <td class="px-6 py-4">
                    @if($booking->payment_method === 'card')
                        <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded flex items-center gap-1 w-fit">
                            <i class="fa-solid fa-credit-card"></i> Carte
                        </span>
                    @elseif($booking->payment_method === 'cash')
                        <span class="text-xs bg-yellow-50 text-yellow-700 px-2 py-1 rounded flex items-center gap-1 w-fit">
                            <i class="fa-solid fa-money-bill"></i> Espèces
                        </span>
                    @else
                        <span class="text-xs bg-purple-50 text-purple-700 px-2 py-1 rounded flex items-center gap-1 w-fit">
                            <i class="fa-solid fa-building-columns"></i> Virement
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($booking->payment_proof)
                        <a href="{{ Storage::url($booking->payment_proof) }}" target="_blank"
                            class="text-xs text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                            <i class="fa-solid fa-file-image"></i> Voir
                        </a>
                    @else
                        <span class="text-xs text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-6 py-4 font-semibold">{{ number_format($booking->total_price, 2) }} MAD</td>
                <td class="px-6 py-4">
                    @if($booking->statut === 'Confirmé')
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded font-medium">
                            <i class="fa-solid fa-check mr-1"></i>Confirmé
                        </span>
                    @elseif($booking->statut === 'En attente')
                        <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded font-medium">
                            <i class="fa-solid fa-clock mr-1"></i>En attente
                        </span>
                    @else
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded font-medium">
                            <i class="fa-solid fa-xmark mr-1"></i>Annulé
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        @if($booking->statut === 'En attente')
                            <form action="{{ route('admin.bookings.validate', $booking->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg font-medium">
                                    <i class="fa-solid fa-check mr-1"></i>Valider
                                </button>
                            </form>
                            <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Rejeter cette réservation ?')">
                                @csrf
                                <button type="submit" class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg font-medium">
                                    <i class="fa-solid fa-xmark mr-1"></i>Rejeter
                                </button>
                            </form>
                        @elseif($booking->statut === 'Confirmé')
                            <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Annuler cette réservation confirmée ?')">
                                @csrf
                                <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium">
                                    Annuler
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400">—</span>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4 border-t">{{ $bookings->links() }}</div>
</div>
@endsection
