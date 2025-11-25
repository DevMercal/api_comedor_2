<?php

namespace App\Http\Controllers;

use App\Models\EmployeeMadePayment;
use App\Http\Requests\StoreEmployeeMadePaymentRequest;
use App\Http\Requests\UpdateEmployeeMadePaymentRequest;

class EmployeeMadePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $employeeMadePayment = EmployeeMadePayment::all();
        if ($employeeMadePayment->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No se encontraron registros'
            ], 404);
        }
        
        return response()->json([
            'status' => 200,
            'MadePayment' => $employeeMadePayment
        ], 200);
    }
    public function show($id)
    {
        try {
            $madePayment  = EmployeeMadePayment::where('id_employee_made_payment' , $id)->first();
            if (!$madePayment) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No se encontro el registro'
                ], 404);
            }

            return response()->json([
                'status' => 200,
                'MadePayment' => $madePayment
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Errores' . $e->getMessage()
            ], 404);
        }
    }
}
