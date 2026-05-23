<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ville;
use App\Models\Route;
use App\Models\Programme;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $villes = Ville::orderBy('name')->get();
        
        $popularRoutes = Route::with(['etapes.gare.ville'])
            ->withCount(['programmes' => function ($query) {
                $query->whereDate('jour_depart', '>=', Carbon::today());
            }])
            ->orderBy('programmes_count', 'desc')
            ->take(6)
            ->get();

        $stats = [
            'cities' => Ville::count(),
            'daily_trips' => Programme::whereDate('jour_depart', Carbon::today())->count(),
            'happy_customers' => 15000 + \App\Models\Reservation::where('statut', 'Confirmé')->count(),
        ];

        return view('welcome', compact('villes', 'popularRoutes', 'stats'));
    }
}
