<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('last_name')->paginate(15);
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'license_number' => 'required|string|unique:employees',
            'phone' => 'required|string',
            'role' => 'required|in:chauffeur,administrateur',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        Employee::create($validated);
        return redirect()->route('admin.employees.index')->with('success', 'Employé ajouté avec succès');
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'license_number' => 'required|string|unique:employees,license_number,' . $employee->id,
            'phone' => 'required|string',
            'role' => 'required|in:chauffeur,administrateur',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $employee->update($validated);
        return redirect()->route('admin.employees.index')->with('success', 'Employé mis à jour');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Employé supprimé');
    }
}
