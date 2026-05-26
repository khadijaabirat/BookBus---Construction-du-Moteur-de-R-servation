<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use App\Models\Route;
use App\Models\Segment;
use App\Models\Etape;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TripController extends Controller
{
    public function index()
    {
        $trips = Programme::with(['route', 'assignments.bus', 'assignments.employee'])
            ->orderBy('jour_depart', 'desc')
            ->paginate(15);
        return view('admin.trips.index', compact('trips'));
    }

    public function create()
    {
        $routes = Route::orderBy('nom')->get();
        return view('admin.trips.create', compact('routes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_id'     => 'required|exists:routes,id',
            'jour_depart'  => 'required|date|after_or_equal:today',
            'heure_depart' => 'required',
            'heure_arrivee'=> 'required',
        ]);

        $route = Route::with('etapes.gare.ville')->findOrFail($request->route_id);

        $prog = Programme::create([
            'route_id'      => $request->route_id,
            'jour_depart'   => $request->jour_depart,
            'heure_depart'  => $request->heure_depart,
            'heure_arrivee' => $request->heure_arrivee,
        ]);

        // Auto-create segments for all stop combinations
        $etapes = $route->etapes;
        for ($i = 0; $i < count($etapes); $i++) {
            for ($j = $i + 1; $j < count($etapes); $j++) {
                $steps = $j - $i;
                $baseTarif = 40;
                $prix = $steps === 1 ? $baseTarif : round($baseTarif * $steps * 0.85, 2);

                Segment::create([
                    'tarif'           => $prix,
                    'duree_estimee'   => sprintf('%02d:00:00', $steps * 3),
                    'distance_km'     => $steps * 100,
                    'bus_id'          => 1, // will be updated after assignment
                    'programme_id'    => $prog->id,
                    'etape_depart_id' => $etapes[$i]->id,
                    'etape_arrivee_id'=> $etapes[$j]->id,
                ]);
            }
        }

        return redirect()->route('admin.trips.index')->with('success', 'Voyage créé. Pensez à affecter un bus et un chauffeur.');
    }

    public function destroy(Programme $trip)
    {
        $trip->delete();
        return redirect()->route('admin.trips.index')->with('success', 'Voyage supprimé.');
    }
}
