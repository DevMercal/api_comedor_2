<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorenumberOrdersDayRequest;
use App\Http\Requests\UpdatenumberOrdersDayRequest;
use App\Http\Resources\numberOrdersDayResource;
use App\Models\numberOrdersDay;

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
        }else {
            return response()->json([
                'status' => 200,
                'numberOrdersDay' => $numberOrdersDay
            ], 200);
        }
    }
    public function store(StorenumberOrdersDayRequest $request)
    {
        return new numberOrdersDayResource(numberOrdersDay::create($request->all()));
    }
    public function show(numberOrdersDay $numberOrdersDay)
    {
        
    }
    public function update(UpdatenumberOrdersDayRequest $request, numberOrdersDay $numberOrdersDay)
    {
        //
    }
}
