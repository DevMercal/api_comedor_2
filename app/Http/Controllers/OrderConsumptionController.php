<?php

namespace App\Http\Controllers;

use App\Models\OrderConsumption;
use App\Http\Requests\StoreOrderConsumptionRequest;
use App\Http\Requests\UpdateOrderConsumptionRequest;
use Exception;
use Illuminate\Support\Facades\Auth;

class OrderConsumptionController extends Controller
{

    public function index()
    {
        if (!Auth::guard('api')->user()->can('view_status_order')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permiso para visualizar los estatus de consumo.'
            ]);
        }
        try {
            $orderConsumption = OrderConsumption::all();
            if ($orderConsumption->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Estatus de consumo no encontradas.'
                ], 404);
            }
            return response()->json([
                'status' => 200,
                'consumption' => $orderConsumption
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Errores a conseguir los registros: ' . $e->getMessage()
            ], 500);
        }
    }
    public function store(StoreOrderConsumptionRequest $request)
    {
        
    }
    public function destroy(OrderConsumption $orderConsumption)
    {
        //
    }
}
