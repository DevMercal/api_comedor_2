<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorenumberOrdersDayRequest;
use App\Http\Requests\UpdatenumberOrdersDayRequest;
use App\Http\Resources\numberOrdersDayResource;
use App\Models\numberOrdersDay;
use App\Models\Order;
use Carbon\Carbon;

class NumberOrdersDayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $dataChecks = Carbon::now()->toDateString();
            $Daylimit = numberOrdersDay::whereDate('date_number_orders', $dataChecks)->first(); 
            if (!$Daylimit) {
                return response()->json([
                    'status' => 409,
                    'message' => 'No se ha configurado el límite de pedidos diario permitido.'
                ], 409);
            }
            $totalAllowed = (int) $Daylimit->numbers_orders_day;
            $totalSold = Order::whereDate('date_order', $dataChecks)->count();
            $remainingTotal = $totalAllowed - $totalSold;
            if ($remainingTotal < 0) {
                $remainingTotal = 0;
            }
            return response()->json([
                'status' => 200,
                'totalAllowed' => $totalAllowed,
                'totalSold' => $totalSold,
                'remainingTotal' => $remainingTotal
            ]);
        
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error de peticiones' . $e->getMessage()
            ]);
        }
    }
    public function store(StorenumberOrdersDayRequest $request)
    {
        $dataChecks = Carbon::now()->toDateString();

        // Usar exists() para verificar si ya hay un registro para hoy
        $recordExists = numberOrdersDay::whereDate('date_number_orders', $dataChecks)->exists();

        if ($recordExists) {
            // Si ya existe un registro, devolver el error 409
            return response()->json([
                'status' => 409,
                'message' => 'Ya se registró un número de ventas para el día de hoy.'
            ], 409);
        } else {
            // Si no existe, crear el nuevo registro
            $newRecord = numberOrdersDay::create($request->all());

            // Devolver la respuesta usando el recurso (si es necesario)
            return new numberOrdersDayResource($newRecord);
        }
    }
    public function update(UpdatenumberOrdersDayRequest $request, $id)
    {
        try {
            $numberOrderDay = numberOrdersDay::where('id_number_orders_days' , $id);
            if (!$numberOrderDay) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Registro no encontrado'
                ], 404);
            }

            $validated = $request->validated();
            $numberOrderDay->update([
                'numbers_orders_day' => $validated['numberOrdersDay'] ?? $numberOrderDay->numbers_orders_day,
                'date_number_orders' => $validated['dateNumberOrders'] ?? $numberOrderDay->date_number_orders
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Registro actualizado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'error' => 'Errores' . $e->getMessage()
            ], 404);
        }
    }
}
