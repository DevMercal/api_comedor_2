<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorenumberOrdersDayRequest;
use App\Http\Requests\UpdatenumberOrdersDayRequest;
use App\Http\Resources\numberOrdersDayResource;
use App\Models\numberOrdersDay;
use Carbon\Carbon;

class NumberOrdersDayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $numberOrdersDay = numberOrdersDay::all();

        if ($numberOrdersDay->isEmpty()) {
            return response()->json([
                'status' => 401,
                'message' => 'No se han cargado la cantidad de pedidos'
            ], 401);
        } else {
            return response()->json([
                'status' => 200,
                'cantidadOrdenes' => $numberOrdersDay
            ], 200);
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
    public function show(numberOrdersDay $numberOrdersDay)
    {
        
    }
    public function update(UpdatenumberOrdersDayRequest $request, numberOrdersDay $numberOrdersDay)
    {
        //
    }
}
