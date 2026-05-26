<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Ville;
use App\Models\Gare;
use App\Models\Etape;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::with(['etapes.gare.ville'])->get();
        return view('admin.routes.index', compact('routes'));
    }

    public function create()
    {
        $villes = Ville::orderBy('name')->get();
        return view('admin.routes.create', compact('villes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => 'required|string|max:50|unique:routes',
            'description' => 'nullable|string',
            'villes'      => 'required|array|min:2',
            'villes.*'    => 'exists:villes,id',
        ]);

        $route = Route::create([
            'nom'         => $request->nom,
            'description' => $request->description,
        ]);

        $villeIds = array_filter($request->villes, fn($id) => is_numeric($id));
        foreach ($villeIds as $index => $villeId) {
            $gare = Gare::where('ville_id', $villeId)->first();
            if ($gare) {
                Etape::create([
                    'route_id'      => $route->id,
                    'gare_id'       => $gare->id,
                    'ordre'         => $index + 1,
                    'heure_passage' => sprintf('%02d:00:00', 6 + ($index * 3)),
                ]);
            }
        }

        return redirect()->route('admin.routes.index')->with('success', 'Ligne créée avec succès.');
    }

    public function destroy(Route $route)
    {
        $route->delete();
        return redirect()->route('admin.routes.index')->with('success', 'Ligne supprimée.');
    }
}
