<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $paymentMethod = PaymentMethod::all();
        if ($paymentMethod->isEmpty()) {
            return response()->json([
                'status' => 409,
                'message' => 'No se encontraron Metodos de Pago'
            ], 409);
        }else {
            return response()->json([
                'status' => 200,
                'paymentMethod' => $paymentMethod
            ], 200);
        }

    }
    public function store(StorePaymentMethodRequest $request)
    {
        //
    }
    
    public function destroy(PaymentMethod $paymentMethod)
    {
        //
    }
}
