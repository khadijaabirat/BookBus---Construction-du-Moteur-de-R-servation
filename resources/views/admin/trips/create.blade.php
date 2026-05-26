@extends('layouts.admin')
@section('title', 'Nouveau Voyage')
@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Créer un Nouveau Voyage</h2>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.trips.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ligne</label>
                    <select name="route_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                        <option value="">-- Choisir une ligne --</option>
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}">{{ $route->nom }} — {{ $route->description }}</option>
                        @endforeach
                    </select>
                    @error('route_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de départ</label>
                    <input type="date" name="jour_depart" value="{{ old('jour_depart', date('Y-m-d')) }}"
                        min="{{ date('Y-m-d') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    @error('jour_depart') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div></div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Heure de départ</label>
                    <input type="time" name="heure_depart" value="{{ old('heure_depart', '08:00') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    @error('heure_depart') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Heure d'arrivée</label>
                    <input type="time" name="heure_arrivee" value="{{ old('heure_arrivee', '14:00') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    @error('heure_arrivee') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                    Créer le Voyage
                </button>
                <a href="{{ route('admin.trips.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
