@extends('layouts.admin')
@section('title', 'Gestion des Voyages')
@section('content')

@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg text-green-700 text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg text-red-700 text-sm">{{ session('error') }}</div>
@endif

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-800">Liste des Voyages</h2>
    <a href="{{ route('admin.trips.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        <i class="fa-solid fa-plus mr-2"></i> Nouveau Voyage
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4">Ligne</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Départ</th>
                    <th class="px-6 py-4">Bus Assigné</th>
                    <th class="px-6 py-4">Chauffeur</th>
                    <th class="px-6 py-4">Statut</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($trips as $trip)
                @php $assignment = $trip->assignments->first(); @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $trip->route->nom }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($trip->jour_depart)->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($trip->heure_depart)->format('H:i') }}</td>
                    <td class="px-6 py-4">
                        @if($assignment)
                            <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $assignment->bus->matricule }}</span>
                            <span class="text-xs text-gray-500 ml-1">({{ ucfirst($assignment->bus->type) }})</span>
                        @else
                            <span class="text-red-500 text-xs"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Non assigné</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($assignment)
                            {{ $assignment->employee->first_name }} {{ $assignment->employee->last_name }}
                        @else
                            <span class="text-red-500 text-xs">Non assigné</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if(\Carbon\Carbon::parse($trip->jour_depart)->isPast())
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">Terminé</span>
                        @else
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Prévu</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.assignments.create', ['programme_id' => $trip->id]) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                                <i class="fa-solid fa-link mr-1"></i>Assigner
                            </a>
                            <form action="{{ route('admin.trips.destroy', $trip) }}" method="POST" class="inline" onsubmit="return confirm('Annuler ce voyage ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">{{ $trips->links() }}</div>
</div>
@endsection
