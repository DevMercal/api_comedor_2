<?php

namespace App\Http\Controllers;

use App\Models\OrderConsumption;
use App\Http\Requests\StoreOrderConsumptionRequest;
use App\Http\Requests\UpdateOrderConsumptionRequest;

class OrderConsumptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
    }
    public function store(StoreOrderConsumptionRequest $request)
    {
        //
    }
    public function destroy(OrderConsumption $orderConsumption)
    {
        //
    }
}
