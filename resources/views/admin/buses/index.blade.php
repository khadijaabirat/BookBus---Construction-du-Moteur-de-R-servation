@extends('layouts.admin')

@section('title', 'Gestion de la Flotte')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-800">Liste des Bus</h2>
    <a href="{{ route('admin.buses.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
        <i class="fa-solid fa-plus mr-2"></i> Ajouter un Bus
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">Matricule</th>
                    <th class="px-6 py-4 font-semibold">Type</th>
                    <th class="px-6 py-4 font-semibold">Capacité</th>
                    <th class="px-6 py-4 font-semibold">Équipements</th>
                    <th class="px-6 py-4 font-semibold">Statut</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach($buses as $bus)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900 font-mono">{{ $bus->matricule }}</td>
                    <td class="px-6 py-4">
                        @if($bus->type === 'premium')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <i class="fa-solid fa-crown mr-1"></i> Premium
                            </span>
                        @elseif($bus->type === 'confort')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Confort</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Standard</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $bus->capacite }} sièges</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-1 text-gray-500">
                            @foreach((array)$bus->amenities as $amenity)
                                @if($amenity == 'wifi') <i class="fa-solid fa-wifi" title="Wi-Fi"></i> 
                                @elseif($amenity == 'prises') <i class="fa-solid fa-plug" title="Prises"></i>
                                @elseif($amenity == 'wc') <i class="fa-solid fa-restroom" title="WC"></i>
                                @elseif($amenity == 'climatisation') <i class="fa-solid fa-snowflake" title="Climatisation"></i>
                                @endif
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($bus->statut === 'disponible')
                            <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-green-500"></span> Disponible</span>
                        @elseif($bus->statut === 'en_route')
                            <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-500"></span> En route</span>
                        @else
                            <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500"></span> Indisponible</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="#" class="text-blue-600 hover:text-blue-800 p-1"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form action="{{ route('admin.buses.destroy', $bus) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 p-1"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $buses->links() }}
    </div>
</div>
@endsection
