<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function login(Request $request){
        try {
            //Validamos los campos
            $validation = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string|min:8',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => 400,
                    'data' => $validation->messages() 
                ], 400);
            }else {
                //Verificar los datos del usuario
                if (Auth::attempt([
                    'email' => $request->email,
                    'password' => $request->password
                ])) {
                    //Traer los datos del usuario
                    $usuario = User::with('employees')->where('email', $request->email)->first();
                    return response()->json([
                        'user' => new UserResource($usuario),
                        'token' => $usuario->createToken('api-key')->plainTextToken
                    ], 200);
                }else {
                    return response()->json([
                    'data' => 'Usuario no encontrado'
                ], 400);
                }
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function index(Request $request)
    {
        $users = User::with('employees')->get();
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|unique:users',
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'cedula' => 'required|numeric|exists:employees,cedula',
                'timeToken' => 'required|numeric|exists:time_tokens,id_time_token',
                'expiryMonth' => 'required|numeric|exists:expiry_months,id_expiry_month'
            ]);
            //Si la validación no se cumple
            if ($validation->fails()) {
                return response()->json([
                    'data' => $validation->messages() 
                ], 400);
            }else {
                $user = User::create([
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'cedula' => $request->cedula,
                    'id_time_token' => $request->timeToken,
                    'id_expiry_month' => $request->expiryMonth,
                    'is_active' => 1
                ]);
                return response()->json([
                    'data' => new UserResource($user),
                    /*'token' => $user->createToken('api-key')->plainTextToken*/
                ], 201);
            }

        } catch (Exception $e) {
           return response()->json([
                'message' => 'Errors Server',
                'errors' => $e->getMessage()
           ], 500);
        }
    }
    public function show($id){
        $registro = User::where('id',$id)->first();
        if (!$registro) {
            return response()->json([
                'status' => 404,
                'message' => 'Error en encontrar usuario'
            ], 404);
        }
        return response()->json([
            'status' => 200,
            'user' => $registro
        ], 200);
    }
    public function destroy($id){
        $registro = User::where('id', $id)->delete();
        if (!$registro) {
            return response()->json([
                'message' => 'Error al eliminar usuario'
            ], 404);
        }
        return response()->json([
            'message' => 'Usuario eliminado correctamente'
        ], 200);
    }
}
