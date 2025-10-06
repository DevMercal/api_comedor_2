<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use App\Http\Requests\StoreEmployeesRequest;
use App\Http\Resources\EmployeesResources;
use App\Http\Requests\UpdateEmployeesRequest;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'management' => 'nullable|integer|exists:management,id_management'
        ]);
        $query = Employees::query()->with('management');
        if (!empty($validated['management'])) {
            $query->where('id_management', $validated['management']);
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
    public function update(UpdateEmployeesRequest $request, $id)
    {
        try {
            $employee = Employees::where('id_employee', $id)->firstOrFail();
            if (!$employee) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Error al encontrar usuario.'
                ]);
            }
            $validated = $request->validated();
            
            $employee->update([
                'first_name' => $validated['firstName'] ?? $employee->first_name,
                'last_name' => $validated['lastName'] ?? $employee->last_name,
                'cedula' => $validated['cedula'] ?? $employee->cedula,
                'id_management' => $validated['management'] ?? $employee->id_management,
                'state' => $validated['state'] ?? $employee->state,
                'type_employee' => $validated['typeEmployee'] ?? $employee->type_employee,
                'position' => $validated['position'] ?? $employee->position,
                'phone' => $validated['phone'] ?? $employee->phone
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Empleado actualizado exitosamente',
                'data' => $employee
            ], 200);
        
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Employees $employees)
    {
        //
    }
}
