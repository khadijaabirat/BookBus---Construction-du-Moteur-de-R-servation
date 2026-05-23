<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Programme;
use App\Models\Bus;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $stats = [
            'total_bookings' => Reservation::where('statut', 'Confirmé')->count(),
            'revenue_today' => Reservation::where('statut', 'Confirmé')
                                ->whereDate('created_at', $today)
                                ->sum('total_price'),
            'revenue_month' => Reservation::where('statut', 'Confirmé')
                                ->whereMonth('created_at', $today->month)
                                ->whereYear('created_at', $today->year)
                                ->sum('total_price'),
            'active_trips_today' => Programme::whereDate('jour_depart', $today)->count(),
            'buses_available' => Bus::where('statut', 'disponible')->count(),
            'buses_total' => Bus::count(),
            'active_drivers' => Employee::where('role', 'chauffeur')->where('is_active', true)->count(),
        ];

        $recentBookings = Reservation::with(['user', 'segment.depart.gare.ville', 'segment.arrivee.gare.ville', 'segment.programme'])
                            ->latest()
                            ->take(5)
                            ->get();

        $upcomingTrips = Programme::with(['route', 'assignments.bus', 'assignments.employee'])
                            ->whereDate('jour_depart', '>=', $today)
                            ->orderBy('jour_depart')
                            ->orderBy('heure_depart')
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'upcomingTrips'));
    }
}
