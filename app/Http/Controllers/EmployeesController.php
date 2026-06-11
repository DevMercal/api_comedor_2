<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use App\Http\Requests\StoreEmployeesRequest;
use App\Http\Resources\EmployeesResources;
use App\Http\Requests\UpdateEmployeesRequest;
use App\Models\Nomina;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeesController extends Controller
{

    public function index(Request $request)
    {
        if (!Auth::guard('api')->user()->can('view_employee')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permisos para visualizar la lista de empleados.'
            ]);
        }
        $query = Employees::all();
        return response()->json([
            'status' => 200,
            'employees' => $query
        ], 200);
    }

    public function store(StoreEmployeesRequest $request)
    {
        if (!Auth::guard('api')->user()->can('view_employee')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permisos para registrar empleados.'
            ]);
        }
        return new EmployeesResources(Employees::create($request->all()));
    }

    public function show($id)
    {
        if (!Auth::guard('api')->user()->can('show_employee')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permisos para visualizar registro de empleado.'
            ]);
        }
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
            
        } catch (Exception $e) {
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
        if (!Auth::guard('api')->user()->can('update_employee')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permisos para editar registro de empleado.'
            ]);
        }
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
                'management' => $validated['management'] ?? $employee->management,
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
        
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el empleado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function syncNomina(){
        try {
            $nominaPgsql = Nomina::all();
            if ($nominaPgsql->isEmpty()) {
                return response()->json([
                    'status' => 409,
                    'errores' => 'No se encontro registros en la vista o no hay conexión.'
                ], 409);
            }

            $insertedCount = 0;

            foreach ($nominaPgsql as $employee) {
                $name = trim($employee->nomemp);
                $parts = explode(',', $name, 2);

                $lastName = '';
                $firstName = '';

                if (count($parts) === 2) {
                    $lastName = trim($parts[0]);
                    $firstName = trim($parts[1]);
                }else {
                    $lastName = $name;
                }

                Employees::updateOrCreate(
                // Condición única para encontrar el registro
                    ['cedula' => $employee->codemp], 
                
                    // Valores a insertar o actualizar:
                    [
                        'cedula' => $employee->codemp,
                        'first_name' => $firstName, // 👈 name_bank(s)
                        'last_name' => $lastName,   // 👈 Apellido(s)
                        'management' => $employee->unidad_adm,
                        'state' => $employee->estado,
                        'type_employee' => $employee->nomina, // Podría usarse 'nomina' para 
                        'position' => $employee->nomcar,
                        'phone' => 'N/A'
                    ]
                );
            $insertedCount++;
            }
            return response()->json([
                'status' => 200,
                'message' => 'Sincronización completa.',
                'total_registros_sincronizados' => $insertedCount
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 404,
                'error' => 'Errores encontrados' . $e->getMessage()
            ], 404);
        }
    }
}
