<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Segment;
use App\Models\Ville;

class TripController extends Controller
{
     public function index()
    {
        $villes = Ville::all();  
        return view('search', compact('villes'));
    }
 
public function search(Request $request)
{
    $trips = Segment::whereHas('depart.gare.ville', function($query) use ($request) {
        $query->where('id', $request->departure_city);
    })
    ->whereHas('arrivee.gare.ville', function($query) use ($request) {
        $query->where('id', $request->arrival_city);
    })
     ->whereHas('programme', function($query) use ($request) {
        $query->whereDate('jour_depart', $request->travel_date);
    })
    ->with(['bus', 'depart.gare.ville', 'arrivee.gare.ville', 'programme'])
    ->get();

    return view('results', compact('trips'));
}
}