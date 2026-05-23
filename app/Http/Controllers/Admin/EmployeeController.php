<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'role' => 'required|string',
            'telephone' => 'nullable|string',
        ]);

        Employee::create($validated);
        return redirect()->route('admin.employees.index')->with('success', 'Employé créé');
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'role' => 'required|string',
            'telephone' => 'nullable|string',
        ]);

        $employee->update($validated);
        return redirect()->route('admin.employees.index')->with('success', 'Employé mis à jour');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Employé supprimé');
    }
}
