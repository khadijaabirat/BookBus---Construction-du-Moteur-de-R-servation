@extends('layouts.admin')
@section('title', isset($employee) ? 'Modifier Employé' : 'Ajouter Employé')
@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">{{ isset($employee) ? 'Modifier' : 'Ajouter' }} un Employé</h2>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ isset($employee) ? route('admin.employees.update', $employee) : route('admin.employees.store') }}" method="POST">
            @csrf
            @if(isset($employee)) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">N° Permis</label>
                    <input type="text" name="license_number" value="{{ old('license_number', $employee->license_number ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone', $employee->phone ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                    <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="chauffeur" {{ (old('role', $employee->role ?? '') == 'chauffeur') ? 'selected' : '' }}>Chauffeur</option>
                        <option value="administrateur" {{ (old('role', $employee->role ?? '') == 'administrateur') ? 'selected' : '' }}>Administrateur</option>
                    </select>
                </div>
                <div class="flex items-end pb-2">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $employee->is_active ?? true) ? 'checked' : '' }}>
                        Employé actif
                    </label>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                    {{ isset($employee) ? 'Mettre à jour' : 'Ajouter' }}
                </button>
                <a href="{{ route('admin.employees.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
