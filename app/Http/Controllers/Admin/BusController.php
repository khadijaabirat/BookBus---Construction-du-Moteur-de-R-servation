<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::orderBy('id', 'desc')->paginate(10);
        return view('admin.buses.index', compact('buses'));
    }

    public function create()
    {
        return view('admin.buses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricule' => 'required|string|max:255|unique:buses',
            'capacite' => 'required|integer|min:10|max:100',
            'type' => 'required|in:standard,confort,premium',
            'statut' => 'required|in:disponible,en_route,en_panne,en_maintenance',
            'amenities' => 'array',
        ]);

        Bus::create($request->all());

        return redirect()->route('admin.buses.index')->with('success', 'Bus ajouté avec succès.');
    }

    public function edit(Bus $bus)
    {
        return view('admin.buses.edit', compact('bus'));
    }

    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'matricule' => 'required|string|max:255|unique:buses,matricule,' . $bus->id,
            'capacite' => 'required|integer|min:10|max:100',
            'type' => 'required|in:standard,confort,premium',
            'statut' => 'required|in:disponible,en_route,en_panne,en_maintenance',
            'amenities' => 'array',
        ]);

        $bus->update($request->all());

        return redirect()->route('admin.buses.index')->with('success', 'Bus mis à jour avec succès.');
    }

    public function destroy(Bus $bus)
    {
        if ($bus->assignments()->exists()) {
            return back()->with('error', 'Impossible de supprimer ce bus car il a des trajets assignés.');
        }
        
        $bus->delete();
        return redirect()->route('admin.buses.index')->with('success', 'Bus supprimé avec succès.');
    }
}
