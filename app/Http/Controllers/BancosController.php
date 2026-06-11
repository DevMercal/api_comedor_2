<?php

namespace App\Http\Controllers;

use App\Models\Bancos;
use App\Http\Requests\StoreBancosRequest;
use App\Http\Requests\UpdateBancosRequest;
use Illuminate\Support\Facades\Auth;

class BancosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::guard('api')->user()->can('view_bank')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permisos para visualizar la lista de bancos.'
            ]);
        }
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
        if (!Auth::guard('api')->user()->can('create_bank')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permisos para visualizar la lista de bancos.'
            ]);
        }
    }

    public function update(UpdateBancosRequest $request, Bancos $bancos)
    {
        if (!Auth::guard('api')->user()->can('update_bank')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permisos para visualizar la lista de bancos.'
            ]);
        }
    }
}
