<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Segment;
use App\Models\Ville;
use Carbon\Carbon;

class TripController extends Controller
{
    public function index()
    {
        $villes = Ville::orderBy('name')->get();
        return view('trips.search', compact('villes'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'departure_city' => 'required|exists:villes,id',
            'arrival_city' => 'required|exists:villes,id|different:departure_city',
            'travel_date' => 'required|date|after_or_equal:today',
        ]);

        $villes = Ville::orderBy('name')->get();
        $departureCity = Ville::find($request->departure_city);
        $arrivalCity = Ville::find($request->arrival_city);
        $travelDate = Carbon::parse($request->travel_date);

        $query = Segment::whereHas('depart.gare.ville', function($q) use ($request) {
                $q->where('id', $request->departure_city);
            })
            ->whereHas('arrivee.gare.ville', function($q) use ($request) {
                $q->where('id', $request->arrival_city);
            })
            ->whereHas('programme', function($q) use ($travelDate) {
                $q->whereDate('jour_depart', $travelDate);
            })
            ->with(['bus', 'depart.gare.ville', 'arrivee.gare.ville', 'programme.route', 'reservations']);

        // Filters
        if ($request->filled('bus_type')) {
            $query->whereHas('bus', function($q) use ($request) {
                $q->whereIn('type', (array) $request->bus_type);
            });
        }

        // Sorting
        $sort = $request->input('sort', 'time_asc');
        $trips = $query->get();

        if ($sort === 'price_asc') {
            $trips = $trips->sortBy('tarif');
        } elseif ($sort === 'price_desc') {
            $trips = $trips->sortByDesc('tarif');
        } else {
            // Default time_asc
            $trips = $trips->sortBy(function($trip) {
                return $trip->programme->heure_depart;
            });
        }

        return view('trips.results', compact('trips', 'villes', 'departureCity', 'arrivalCity', 'travelDate'));
    }
}