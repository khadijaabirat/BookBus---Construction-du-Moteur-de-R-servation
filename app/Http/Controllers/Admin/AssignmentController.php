<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Programme;
use App\Models\Bus;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    public function create(Request $request)
    {
        $programme = Programme::with('route')->findOrFail($request->programme_id);
        $date = Carbon::parse($programme->jour_depart)->toDateString();

        // Buses available: statut=disponible AND not already assigned on this date
        $assignedBusIds = Assignment::where('date', $date)->pluck('bus_id');
        $availableBuses = Bus::where('statut', 'disponible')
            ->whereNotIn('id', $assignedBusIds)
            ->get();

        // Drivers available: active AND total driving hours < 10h on this date
        $tripDuration = Carbon::parse($programme->heure_depart)
            ->diffInHours(Carbon::parse($programme->heure_arrivee));

        $busyDriverIds = Assignment::where('date', $date)
            ->with('programme')
            ->get()
            ->groupBy('employee_id')
            ->filter(function ($assignments) use ($tripDuration) {
                $totalHours = $assignments->sum(function ($a) {
                    return Carbon::parse($a->programme->heure_depart)
                        ->diffInHours(Carbon::parse($a->programme->heure_arrivee));
                });
                return ($totalHours + $tripDuration) > 10;
            })
            ->keys();

        $availableDrivers = Employee::where('role', 'chauffeur')
            ->where('is_active', true)
            ->whereNotIn('id', $busyDriverIds)
            ->get();

        return view('admin.assignments.create', compact('programme', 'availableBuses', 'availableDrivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'programme_id' => 'required|exists:programmes,id',
            'bus_id'       => 'required|exists:buses,id',
            'employee_id'  => 'required|exists:employees,id',
        ]);

        $programme = Programme::findOrFail($request->programme_id);
        $date = Carbon::parse($programme->jour_depart)->toDateString();

        // Rule 1: Un bus ne peut être affecté qu'à un trajet à la fois
        $busConflict = Assignment::where('bus_id', $request->bus_id)
            ->where('date', $date)
            ->exists();
        if ($busConflict) {
            return back()->with('error', 'Ce bus est déjà affecté à un autre trajet ce jour-là.');
        }

        // Rule 2: Chauffeur max 10h/jour
        $tripDuration = Carbon::parse($programme->heure_depart)
            ->diffInHours(Carbon::parse($programme->heure_arrivee));

        $driverHours = Assignment::where('employee_id', $request->employee_id)
            ->where('date', $date)
            ->with('programme')
            ->get()
            ->sum(function ($a) {
                return Carbon::parse($a->programme->heure_depart)
                    ->diffInHours(Carbon::parse($a->programme->heure_arrivee));
            });

        if (($driverHours + $tripDuration) > 10) {
            return back()->with('error', "Ce chauffeur dépasserait la limite de 10h de conduite/jour ({$driverHours}h déjà planifiées).");
        }

        Assignment::updateOrCreate(
            ['programme_id' => $request->programme_id],
            [
                'bus_id'      => $request->bus_id,
                'employee_id' => $request->employee_id,
                'date'        => $date,
            ]
        );

        return redirect()->route('admin.trips.index')->with('success', 'Bus et chauffeur affectés avec succès.');
    }
}
