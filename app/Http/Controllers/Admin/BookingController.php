<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'segment.depart.gare.ville', 'segment.arrivee.gare.ville'])
            ->orderBy('created_at', 'desc');

        $allowedStatuts = ['Confirmé', 'Annulé', 'En attente'];
        if ($request->filled('statut') && in_array($request->statut, $allowedStatuts)) {
            $query->where('statut', $request->statut);
        }

        $bookings = $query->paginate(15)->withQueryString();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Reservation::with(['user', 'segment.depart.gare.ville', 'segment.arrivee.gare.ville', 'segment.programme', 'segment.bus'])
            ->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function validatePayment($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->statut !== 'En attente') {
            return back()->with('error', 'Cette réservation n\'est pas en attente.');
        }

        $reservation->update(['statut' => 'Confirmé']);
        return back()->with('success', 'Paiement validé. Réservation confirmée.');
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        if ($reservation->statut === 'Annulé') {
            return back()->with('error', 'Cette réservation est déjà annulée.');
        }

        $reservation->update([
            'statut' => 'Annulé',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Réservation annulée.');
    }
}
