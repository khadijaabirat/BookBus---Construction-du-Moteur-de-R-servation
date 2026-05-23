@extends('layouts.admin')
@section('title', 'Gestion des Employés')
@section('content')

@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded text-green-700 text-sm">{{ session('success') }}</div>
@endif

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-800">Chauffeurs & Employés</h2>
    <a href="{{ route('admin.employees.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        <i class="fa-solid fa-plus mr-2"></i> Ajouter
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 border-b text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-6 py-4">Nom</th>
                <th class="px-6 py-4">Rôle</th>
                <th class="px-6 py-4">N° Permis</th>
                <th class="px-6 py-4">Téléphone</th>
                <th class="px-6 py-4">Statut</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($employees as $emp)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium">{{ $emp->first_name }} {{ $emp->last_name }}</td>
                <td class="px-6 py-4">
                    <span class="text-xs px-2 py-1 rounded {{ $emp->role === 'chauffeur' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                        {{ ucfirst($emp->role) }}
                    </span>
                </td>
                <td class="px-6 py-4 font-mono text-xs">{{ $emp->license_number }}</td>
                <td class="px-6 py-4">{{ $emp->phone }}</td>
                <td class="px-6 py-4">
                    @if($emp->is_active)
                        <span class="flex items-center gap-1.5 text-xs text-green-700"><span class="w-2 h-2 rounded-full bg-green-500"></span>Actif</span>
                    @else
                        <span class="flex items-center gap-1.5 text-xs text-red-700"><span class="w-2 h-2 rounded-full bg-red-500"></span>Inactif</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.employees.edit', $emp) }}" class="text-blue-600 hover:text-blue-800 mr-3"><i class="fa-solid fa-pen-to-square"></i></a>
                    <form action="{{ route('admin.employees.destroy', $emp) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4 border-t">{{ $employees->links() }}</div>
</div>
@endsection
