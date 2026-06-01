<?php

namespace App\Http\Controllers;

use App\Models\timeTokens;
use App\Models\User;
use Carbon\Carbon;
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

        RateLimiter::clear($throttleKey);
        $user = Auth::guard('api')->user();

        $mesesConfigurados = (int) ($user->expiryMonth->monts ?? 3);

        $lastChange = $user->updated_at
        ? Carbon::parse($user->updated_at)
        : $user->updated_at;

        $dateExpiration = $lastChange->addMonths($mesesConfigurados);
        $passwordExpired = now()->greaterThan($dateExpiration);

        $esClaveInicial = Hash::check($user->cedula, $user->password);
        $mustChangePassword = $esClaveInicial || $passwordExpired;

        $reason = null;
        if ($esClaveInicial) {
            $reason = 'initial_password';
        }else {
            $reason = 'expired_password';
        }

        $token = $this->setTokenTTLAndGenerate($user);

        return response()->json([
        'status' => $mustChangePassword ? 'must_change_password' : 'success',
        'reason' => $reason,
        'message' => $passwordExpired 
                    ? "Su contraseña ha expirado (Política de cada {$mesesConfigurados} meses)." 
                    : ($esClaveInicial ? 'Debe cambiar su clave inicial por seguridad.' : 'Login exitoso'),
        'token' => $mustChangePassword ? 'Cambio de clave requerido.' : $token,
        'user' => $mustChangePassword ? $user : $this->getUserDataWithPermissions($user, $dateExpiration),
    ], 200, [], JSON_UNESCAPED_UNICODE);

    }
    public function me()
    {
        return response()->json($this->getUserDataWithPermissions(auth('api')->user()));
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function getUserDataWithPermissions($user, $dateExpiration = null)
    {
        if (!$user) return null;

        if (!$dateExpiration) {
            $mesesConfigurados = (int) ($user->expiryMonth?->monts ?? 3);
            $lastChange = $user->updated_at ? Carbon::parse($user->updated_at) : now();
            $dateExpiration = $lastChange->addMonths($mesesConfigurados);
        }
        return [
            'id' => $user->id,
            'cedula' => $user->cedula ?? null,
            'email' => $user->email,
            'id_token' => $user->id_time_token ?? null,
            'description_token' => $user->timeToken?->description ?? null,
            //'roles' => $user->getRoleNames(),
            //'permissions' => $user->getAllPermissions()->pluck('name'),
            'password_expries' => $dateExpiration->format('Y-m-d'),
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }

    protected function setTokenTTLAndGenerate($user){
        $tiempoMins = timeTokens::where('id_time_token', $user->id_time_token)->value('time_token') ?? 60;
        auth('api')->factory()->setTTL($tiempoMins);
        return auth('api')->tokenById($user->id);
    }
}
