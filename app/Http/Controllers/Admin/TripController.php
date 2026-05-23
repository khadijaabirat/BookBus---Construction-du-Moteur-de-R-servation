<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index()
    {
        $trips = Programme::with(['route', 'bus'])->get();
        return view('admin.trips.index', compact('trips'));
    }

    public function create()
    {
        return view('admin.trips.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'bus_id' => 'required|exists:buses,id',
            'jour_depart' => 'required|date',
            'heure_depart' => 'required',
            'heure_arrivee' => 'required',
        ]);

        Programme::create($validated);
        return redirect()->route('admin.trips.index')->with('success', 'Voyage créé');
    }

    public function edit(Programme $trip)
    {
        return view('admin.trips.edit', compact('trip'));
    }

    public function update(Request $request, Programme $trip)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'bus_id' => 'required|exists:buses,id',
            'jour_depart' => 'required|date',
            'heure_depart' => 'required',
            'heure_arrivee' => 'required',
        ]);

        $trip->update($validated);
        return redirect()->route('admin.trips.index')->with('success', 'Voyage mis à jour');
    }

    public function destroy(Programme $trip)
    {
        $trip->delete();
        return redirect()->route('admin.trips.index')->with('success', 'Voyage supprimé');
    }
}
