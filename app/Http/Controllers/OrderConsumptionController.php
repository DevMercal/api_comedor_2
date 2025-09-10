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
        //
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderConsumptionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderConsumption $orderConsumption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderConsumption $orderConsumption)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderConsumptionRequest $request, OrderConsumption $orderConsumption)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderConsumption $orderConsumption)
    {
        //
    }
}
