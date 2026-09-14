<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\numberOrdersDay;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\numberOrdersDayResource;
use App\Http\Requests\StorenumberOrdersDayRequest;
use App\Http\Requests\UpdatenumberOrdersDayRequest;

class NumberOrdersDayController extends Controller
{

    public function index()
    {
        /*if (!Auth::guard('api')->user()->can('view_number_order_day')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permiso para ver la cantidad de pedidos por dia.'
            ], 403);
        }*/
        try {
            $dataChecks = Carbon::now()->toDateString();
            $Daylimit = numberOrdersDay::whereDate('date_number_orders', $dataChecks)->first(); 
            if (!$Daylimit) {
                return response()->json([
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
                'totalAllowed' => $totalAllowed,
                'totalSold' => $totalSold,
                'remainingTotal' => $remainingTotal
            ], 200);
        
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error de peticiones' . $e->getMessage()
            ], 500);
        }
    }
    public function store(StorenumberOrdersDayRequest $request)
    {
        /*if (!Auth::guard('api')->user()->can('create_number_order_day')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permiso para registar la cantidad de venta de pedidos.'
            ], 403);
        }*/
        try {    
            $dataChecks = Carbon::now()->toDateString();

            // Usar exists() para verificar si ya hay un registro para hoy
            $recordExists = numberOrdersDay::whereDate('date_number_orders', $dataChecks)->exists();

            if ($recordExists) {
                // Si ya existe un registro, devolver el error 409
                return response()->json([
                    'message' => 'Ya se registró un número de ventas para el día de hoy.'
                ], 409);
            } else {
                // Si no existe, crear el nuevo registro
                $newRecord = numberOrdersDay::create($request->all());

                // Devolver la respuesta usando el recurso (si es necesario)
                return new numberOrdersDayResource($newRecord);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error en registrar cantidad de pedidos: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update(UpdatenumberOrdersDayRequest $request, $id)
    {
        if (!Auth::guard('api')->user()->can('update_number_order_day')) {
            return response()->json([
                'message' => 'No tiene permiso para editar la cantidad de pedidos.'
            ], 403);
        }
        try {
            $numberOrderDay = numberOrdersDay::where('id_number_orders_days' , $id);
            if (!$numberOrderDay) {
                return response()->json([
                    'message' => 'Registro no encontrado'
                ], 404);
            }

            $validated = $request->validated();
            $numberOrderDay->update([
                'numbers_orders_day' => $validated['numberOrdersDay'] ?? $numberOrderDay->numbers_orders_day,
                'date_number_orders' => $validated['dateNumberOrders'] ?? $numberOrderDay->date_number_orders
            ]);

            return response()->json([
                'message' => 'Registro actualizado exitosamente'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Errores al editar cantida de pedidos: ' . $e->getMessage()
            ], 500);
        }
    }
}
