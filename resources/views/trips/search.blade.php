@extends('layouts.app')

@section('content')
<div class="bg-satas-navy py-12 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6">Trouvez votre trajet</h1>
        
        <div class="card-glass p-6">
            <form action="{{ route('search.trip') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm text-gray-200 mb-1">Départ</label>
                    <select name="departure_city" class="form-select w-full !text-gray-900" required>
                        <option value="" disabled selected>Ville de départ</option>
                        @foreach($villes as $ville)
                            <option value="{{ $ville->id }}">{{ $ville->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1">
                    <label class="block text-sm text-gray-200 mb-1">Arrivée</label>
                    <select name="arrival_city" class="form-select w-full !text-gray-900" required>
                        <option value="" disabled selected>Ville d'arrivée</option>
                        @foreach($villes as $ville)
                            <option value="{{ $ville->id }}">{{ $ville->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1">
                    <label class="block text-sm text-gray-200 mb-1">Date du voyage</label>
                    <input type="date" name="travel_date" class="form-input w-full !text-gray-900" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full md:w-auto">
                        <i class="fa-solid fa-search"></i> Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
