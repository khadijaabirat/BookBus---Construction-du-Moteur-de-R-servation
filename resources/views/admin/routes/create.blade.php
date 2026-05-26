@extends('layouts.admin')
@section('title', 'Nouvelle Ligne')
@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Créer une Nouvelle Ligne</h2>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.routes.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code Ligne</label>
                    <input type="text" name="nom" placeholder="Ex: L101" value="{{ old('nom') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase" required>
                    @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" name="description" placeholder="Ex: Casablanca - Marrakech" value="{{ old('description') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Arrêts (dans l'ordre du trajet)
                    <span class="text-gray-400 font-normal ml-1">— minimum 2 villes</span>
                </label>
                <div id="stops-container" class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-red-100 text-red-600 text-xs flex items-center justify-center font-bold flex-shrink-0">1</span>
                        <select name="villes[]" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                            <option value="">-- Ville de départ --</option>
                            @foreach($villes as $ville)
                                <option value="{{ $ville->id }}">{{ $ville->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-red-100 text-red-600 text-xs flex items-center justify-center font-bold flex-shrink-0">2</span>
                        <select name="villes[]" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                            <option value="">-- Ville d'arrivée --</option>
                            @foreach($villes as $ville)
                                <option value="{{ $ville->id }}">{{ $ville->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="button" onclick="addStop()"
                    class="mt-3 text-sm text-red-600 hover:text-red-800 font-medium flex items-center gap-1">
                    <i class="fa-solid fa-plus"></i> Ajouter un arrêt intermédiaire
                </button>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                    Créer la Ligne
                </button>
                <a href="{{ route('admin.routes.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let stopCount = 2;
const villes = @json($villes);

function addStop() {
    stopCount++;
    const container = document.getElementById('stops-container');
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2';
    div.innerHTML = `
        <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-600 text-xs flex items-center justify-center font-bold flex-shrink-0">${stopCount}</span>
        <select name="villes[]" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">-- Arrêt intermédiaire --</option>
            ${villes.map(v => `<option value="${v.id}">${v.name}</option>`).join('')}
        </select>
        <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600">
            <i class="fa-solid fa-times"></i>
        </button>
    `;
    // Insert before last stop
    const stops = container.children;
    container.insertBefore(div, stops[stops.length - 1]);
}
</script>
@endsection
