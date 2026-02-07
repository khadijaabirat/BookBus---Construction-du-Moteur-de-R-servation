<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Segment;

class ReservationController extends Controller
{
    public function store(Request $request, $segment_id)
    {
         $request->validate([
            'seats' => 'required|array|min:1',
        ]);

        $newReservationIds = [];

         foreach ($request->seats as $seatNumber) {
            
             $isAlreadyTaken = Reservation::where('segment_id', $segment_id)
                                        ->where('siege_numero', $seatNumber)
                                        ->exists();

            if (!$isAlreadyTaken) {
                $res = Reservation::create([
                    'siege_numero' => $seatNumber,
                    'date_reservation' => now(),
                    'statut' => 'Confirmé',
                    'segment_id' => $segment_id,
                    'user_id' => 1,      
                    'reference' => 'SATAS-' . strtoupper(uniqid())
                ]);
                $newReservationIds[] = $res->id;
            }
        }

         if (empty($newReservationIds)) {
            return back()->with('error', 'Désolé, ces places sont déjà prises.');
        }

         return redirect()->route('reservation.success', ['ids' => implode(',', $newReservationIds)])
                         ->with('success', 'Réservation effectuée !');
    }

    public function success(Request $request)
    {
         $ids = explode(',', $request->query('ids'));
        
         $reservations = Reservation::with([
            'segment.depart.gare.ville', 
            'segment.arrivee.gare.ville', 
            'segment.programme'
        ])->whereIn('id', $ids)->get();

        if ($reservations->isEmpty()) {
            return redirect()->route('search.index');
        }

        return view('success', compact('reservations'));
    }
}