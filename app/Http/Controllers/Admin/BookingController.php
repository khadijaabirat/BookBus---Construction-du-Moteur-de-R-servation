<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Reservation::with(['user', 'segment.depart.gare.ville', 'segment.arrivee.gare.ville'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Reservation::with(['user', 'segment.depart.gare.ville', 'segment.arrivee.gare.ville', 'segment.programme', 'segment.bus'])
            ->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
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
