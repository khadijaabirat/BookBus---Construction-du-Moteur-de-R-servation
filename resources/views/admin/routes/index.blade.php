@extends('layouts.admin')
@section('title', 'Gestion des Lignes')
@section('content')

@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded text-green-700 text-sm">{{ session('success') }}</div>
@endif

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-800">Lignes SATAS</h2>
    <a href="{{ route('admin.routes.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        <i class="fa-solid fa-plus mr-2"></i> Nouvelle Ligne
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 border-b text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-6 py-4">Code</th>
                <th class="px-6 py-4">Description</th>
                <th class="px-6 py-4">Arrêts</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($routes as $route)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-bold font-mono text-gray-900">{{ $route->nom }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $route->description }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1 flex-wrap">
                        @foreach($route->etapes as $i => $etape)
                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                {{ $etape->gare->ville->name }}
                            </span>
                            @if(!$loop->last)
                                <i class="fa-solid fa-arrow-right text-gray-300 text-xs"></i>
                            @endif
                        @endforeach
                    </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <form action="{{ route('admin.routes.destroy', $route) }}" method="POST" class="inline"
                        onsubmit="return confirm('Supprimer cette ligne ?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">
                            <i class="fa-solid fa-trash mr-1"></i>Supprimer
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
