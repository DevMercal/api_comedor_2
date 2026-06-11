<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Exception;

class PermissionController extends Controller
{
    //
    public function index(){
        if (!Auth::guard('api')->user()->can('view permissions')) {
            return response()->json([
                'status' => 403,
                'message' => 'No esta autorizado a visualizar los permisos.'
            ],403);
        }
        try {
            $permissions = Permission::all();
            if ($permissions->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'meesage' => 'NO se encontraron registros de permisos'
                ], 404);
            }else {
                return response()->json([
                    'status' => 200,
                    'permissions' => $permissions
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
