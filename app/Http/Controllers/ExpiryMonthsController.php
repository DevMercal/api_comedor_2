<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreexpiryMonthsRequest;
use App\Http\Requests\UpdateexpiryMonthsRequest;
use App\Models\expiryMonths;
use Exception;
use Illuminate\Support\Facades\Auth;

class ExpiryMonthsController extends Controller
{

    public function index()
    {
        /*if (!Auth::guard('api')->user()->can('view_expiry_month')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permiso para visualizar los meses de expiración de contraseñas.'
            ]);
        }*/
        try {
            $expiryMonths = expiryMonths::all();
            if ($expiryMonths->isEmpty()) {
                return response()->json([
                    'message' => 'No se encuetran registros.'
                ], 200); 
            }else {
                return response()->json([
                    'expiryMonths' => $expiryMonths
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error Server',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreexpiryMonthsRequest $request)
    {
        //
    }

    public function update(UpdateexpiryMonthsRequest $request, expiryMonths $expiryMonths)
    {
        //
    }

}
