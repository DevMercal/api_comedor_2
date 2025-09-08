<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use App\Http\Requests\StoreEmployeesRequest;
use App\Http\Requests\UpdateEmployeesRequest;
use App\Http\Resources\EmployeesResources;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'gerencias' => 'nullable|integer|exists:management,id_management'
        ]);
        $query = Employees::query()->with('gerencias');
        if (!empty($validated['gerencias'])) {
            $query->where('id_management', $validated['gerencias']);
        }
        //$employes = $query->paginate(20);
        return response()->json([
            'status' => 200,
            'employees' => $query->get()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeesRequest $request)
    {
        //
        return new EmployeesResources(Employees::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $employee = Employees::where('id_employee', $id)->fisrt();
            if ($employee->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No se encontro el empleado'
                ], 404);
            }else {
                return response()->json([
                    'status' => 200,
                    'employee' => $employee
                ], 200);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Error al encontrar el registor ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeesRequest $request, Employees $employees)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employees $employees)
    {
        //
    }
}
