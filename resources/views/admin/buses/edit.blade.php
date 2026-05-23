@extends('layouts.admin')
@section('title', isset($bus) ? 'Modifier Bus' : 'Ajouter Bus')
@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">{{ isset($bus) ? 'Modifier le Bus' : 'Ajouter un Bus' }}</h2>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ isset($bus) ? route('admin.buses.update', $bus) : route('admin.buses.store') }}" method="POST">
            @csrf
            @if(isset($bus)) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Matricule</label>
                    <input type="text" name="matricule" value="{{ old('matricule', $bus->matricule ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    @error('matricule') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capacité</label>
                    <input type="number" name="capacite" value="{{ old('capacite', $bus->capacite ?? 55) }}" min="10" max="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                        <option value="standard" {{ (old('type', $bus->type ?? '') == 'standard') ? 'selected' : '' }}>Standard</option>
                        <option value="confort" {{ (old('type', $bus->type ?? '') == 'confort') ? 'selected' : '' }}>Confort</option>
                        <option value="premium" {{ (old('type', $bus->type ?? '') == 'premium') ? 'selected' : '' }}>Premium (+20%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="statut" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                        <option value="disponible" {{ (old('statut', $bus->statut ?? '') == 'disponible') ? 'selected' : '' }}>Disponible</option>
                        <option value="en_route" {{ (old('statut', $bus->statut ?? '') == 'en_route') ? 'selected' : '' }}>En Route</option>
                        <option value="en_maintenance" {{ (old('statut', $bus->statut ?? '') == 'en_maintenance') ? 'selected' : '' }}>En Maintenance</option>
                        <option value="en_panne" {{ (old('statut', $bus->statut ?? '') == 'en_panne') ? 'selected' : '' }}>En Panne</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Équipements</label>
                <div class="flex flex-wrap gap-4">
                    @foreach(['wifi' => 'Wi-Fi', 'prises' => 'Prises USB', 'wc' => 'WC', 'climatisation' => 'Climatisation', 'tablette' => 'Tablette'] as $val => $label)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="amenities[]" value="{{ $val }}"
                            {{ in_array($val, old('amenities', $bus->amenities ?? [])) ? 'checked' : '' }}>
                        {{ $label }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                    {{ isset($bus) ? 'Mettre à jour' : 'Ajouter' }}
                </button>
                <a href="{{ route('admin.buses.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
