<?php

namespace App\Http\Controllers;

use App\Models\Bancos;
use App\Http\Requests\StoreBancosRequest;
use App\Http\Requests\UpdateBancosRequest;

class BancosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $banks = Bancos::all();
            if ($banks->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No se encontraron registros' 
                ], 404);
            }

            return response()->json([
                'status' => 200,
                'bank' => $banks
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Error' . $e->getMessage()
            ]);
        }
    }

    public function store(StoreBancosRequest $request)
    {
        //
    }
    public function show(Bancos $bancos)
    {
        //
    }

    public function update(UpdateBancosRequest $request, Bancos $bancos)
    {
        //
    }
    public function destroy(Bancos $bancos)
    {
        //
    }
}
