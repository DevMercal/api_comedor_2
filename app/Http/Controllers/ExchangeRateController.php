<?php

// app/Http/Controllers/ExchangeRateController.php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExchangeRateController extends Controller
{
    /**
     * Devuelve la tasa del dólar más reciente.
     */
    public function latest()
    {
        /*if (!Auth::guard('api')->user()->can('view_today_rate')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permisos para visualizar la tasa del BCV.'
            ]);
        }*/
        // 1. Buscar la tasa más reciente por fecha.
        $latestRate = ExchangeRate::orderBy('date', 'desc')->first();

        // 2. Verificar si se encontró un registro.
        if (!$latestRate) {
            return response()->json([
                'message' => 'No se encontró una tasa de cambio registrada.',
            ], 200);
        }

        // 3. Devolver el registro como respuesta JSON.
        return response()->json([
            'status' => 'success',
            'data' => [
                'currency' => $latestRate->currency,
                'rate' => (float) $latestRate->rate, // Asegúrate de que se devuelva como número
                'date' => $latestRate->date,
                'retrieved_at' => $latestRate->created_at,
            ]
        ]);
    }
}