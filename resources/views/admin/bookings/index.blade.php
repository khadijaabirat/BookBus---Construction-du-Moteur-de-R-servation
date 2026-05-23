@extends('layouts.admin')
@section('title', 'Gestion des Réservations')
@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-800">Toutes les Réservations</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 border-b text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-6 py-4">Référence</th>
                <th class="px-6 py-4">Client</th>
                <th class="px-6 py-4">Trajet</th>
                <th class="px-6 py-4">Date</th>
                <th class="px-6 py-4">Prix</th>
                <th class="px-6 py-4">Statut</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($bookings as $booking)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-mono text-xs font-bold">{{ $booking->reference }}</td>
                <td class="px-6 py-4">{{ $booking->user->name ?? 'N/A' }}</td>
                <td class="px-6 py-4 text-xs">
                    {{ $booking->segment->depart->gare->ville->name }}
                    <i class="fa-solid fa-arrow-right mx-1 text-gray-400"></i>
                    {{ $booking->segment->arrivee->gare->ville->name }}
                </td>
                <td class="px-6 py-4 text-xs">{{ \Carbon\Carbon::parse($booking->date_reservation)->format('d/m/Y') }}</td>
                <td class="px-6 py-4 font-semibold">{{ number_format($booking->total_price, 2) }} MAD</td>
                <td class="px-6 py-4">
                    @if($booking->statut === 'Confirmé')
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Confirmé</span>
                    @else
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">Annulé</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    @if($booking->statut !== 'Annulé')
                    <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Annuler cette réservation ?');">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Annuler</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4 border-t">{{ $bookings->links() }}</div>
</div>
@endsection
