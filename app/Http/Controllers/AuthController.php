<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    function login (Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Error' => 422,
                $validator ->errors()
            ], 422);
        }

        $userSearch = User::where('email', $request->email)->first();

        $errorResponse = !$userSearch 
        ? response()->json([
            'status' => 404,
            'message' => 'Usuario no encontrado'
        ], 404) 
        : (!$userSearch->is_active 
            ? response()->json([
                'status' => 403,
                'message' => 'Su cuenta se encuentra bloqueada por seguridad. Por favor, comuníquese con Seguridad Lógica.'
            ], 403) 
            : null);
        if ($errorResponse) return $errorResponse;
        if (!$request->password === '12345678') {
            return response()->json([
                'stauts' => 404,
                'message' => 'Por razones de seguidad, no puede iniciar sesión con la clave perdeterminada.'
            ]);
        }

        $throttleKey = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());

        $credentials = $request->only('email', 'password');
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            RateLimiter::hit($throttleKey, 3600); 

            if (RateLimiter::attempts($throttleKey) >= 5) {
                $userSearch->update(['is_active' => false]);
                return response()->json([
                    'error' => 'Cuenta bloqueada',
                    'message' => 'Ha excedido el límite de 5 intentos fallidos. Cuenta desactivada.'
                ], 403);
            }

            $attemptsLeft = 5 - RateLimiter::attempts($throttleKey);
            return response()->json([
                'error' => 'Credenciales inválidas',
                'message' => "Contraseña incorrecta. Le quedan {$attemptsLeft} intentos."
            ], 401);
        }



    }
}
