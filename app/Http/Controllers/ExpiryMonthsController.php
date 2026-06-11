<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreexpiryMonthsRequest;
use App\Http\Requests\UpdateexpiryMonthsRequest;
use App\Models\expiryMonths;
use Illuminate\Support\Facades\Auth;

class ExpiryMonthsController extends Controller
{

    public function index()
    {
        if (!Auth::guard('api')->user()->can('view_expiry_month')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permiso para visualizar los meses de expiración de contraseñas.'
            ]);
        }
        $expiryMonths = expiryMonths::all();
        if ($expiryMonths->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No se encuetran registros.'
            ]); 
        }else {
            return response()->json([
                'status' => 200,
                'expiryMonths' => $expiryMonths
            ]);
        }
    }

    public function store(StoreexpiryMonthsRequest $request)
    {
        //
    }

    public function show(expiryMonths $expiryMonths)
    {
        //
    }

    public function update(UpdateexpiryMonthsRequest $request, expiryMonths $expiryMonths)
    {
        //
    }

}
