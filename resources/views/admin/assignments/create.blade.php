@extends('layouts.admin')
@section('title', 'Affecter Bus & Chauffeur')
@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-xl font-semibold text-gray-800 mb-2">Affecter Bus & Chauffeur</h2>
    <p class="text-sm text-gray-500 mb-6">Voyage: <strong>{{ $programme->route->nom }}</strong> — {{ \Carbon\Carbon::parse($programme->jour_depart)->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($programme->heure_depart)->format('H:i') }}</p>

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded text-red-700 text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.assignments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="programme_id" value="{{ $programme->id }}">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Bus Disponible</label>
                <select name="bus_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">-- Choisir un bus --</option>
                    @foreach($availableBuses as $bus)
                    <option value="{{ $bus->id }}">
                        {{ $bus->matricule }} — {{ ucfirst($bus->type) }} ({{ $bus->capacite }} sièges)
                        @if($bus->type === 'premium') ⭐ +20% @endif
                    </option>
                    @endforeach
                </select>
                @if($availableBuses->isEmpty())
                <p class="text-red-500 text-xs mt-1">⚠️ Aucun bus disponible pour cette date.</p>
                @endif
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Chauffeur Disponible</label>
                <select name="employee_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">-- Choisir un chauffeur --</option>
                    @foreach($availableDrivers as $driver)
                    <option value="{{ $driver->id }}">
                        {{ $driver->first_name }} {{ $driver->last_name }} — {{ $driver->license_number }}
                    </option>
                    @endforeach
                </select>
                @if($availableDrivers->isEmpty())
                <p class="text-red-500 text-xs mt-1">⚠️ Aucun chauffeur disponible (limite 10h/jour atteinte).</p>
                @endif
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                    <i class="fa-solid fa-link mr-2"></i>Affecter
                </button>
                <a href="{{ route('admin.trips.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
