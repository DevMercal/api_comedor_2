<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Exception;

class RoleController extends Controller
{
    public function index()
    {
        $user = Auth::guard('api')->user();

        // 1. Verificación de permiso básica
        if (!$user->can('view roles')) {
            return response()->json([
                'status' => 403,
                'message' => 'No está autorizado a visualizar los roles'
            ], 403);
        }

        try {
            $query = Role::with('permissions');
            // Usamos nombres exactos según tus imágenes: "Super-Admin" y "Admin"
            if ($user->hasRole('Super-Admin')) {
                // El Super-Admin no tiene filtros, ve todo.
            } elseif ($user->hasRole('Admin') || $user->hasRole('ADMIN')) {
                // El Admin NO debe ver al Super-Admin
                // Usamos where que no sea "like" para evitar errores de coincidencia parcial
                $query->where('name', '!=', 'Super-Admin');
            } else {
                // Cualquier otro rol (como User o securityLogical)
                $query->where('name', '!=', 'Super-Admin')
                    ->where('name', '!=', 'Admin')
                    ->where('name', '!=', 'ADMIN');
            }

            $roles = $query->get();

            if ($roles->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No se encontraron registros de roles.'
                ], 404);
            }

            return response()->json([
                'status' => 200,
                'roles' => $roles
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500, // Cambiado a 500 porque es un error de servidor/excepción
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request){
        if (!Auth::guard('api')->user()->can('create roles')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene autorización para crear roles.'
            ], 403);
        }

        try {
            $validated = $request->validate([
            'name' => 'required|string|unique:roles|max:255',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,name'
        ]);
        
        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'api']);

        $userRole = Role::where('name', 'User')->where('guard_name', 'api')->first();

        $basePermissions = $userRole ? $userRole->permissions->pluck('name')->toArray() : [];

        $requestedPermissions = $validated['permissions'] ?? [];

        $finalPermissions = array_unique(array_merge($basePermissions, $requestedPermissions));
        
        $role->syncPermissions($finalPermissions);

        $role->load('permissions');

        return response()->json([
            'status' => 200,
            'message' => 'Rol creado correctamente'
        ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id){
        if (!Auth::guard('api')->user()->can('view roles')) {
            return response()->json([
                'status' => 403,
                'message' => 'No esta autorizado para ver el rol.'
            ], 403);
        }
        try {
            $role = Role::with('permissions')->findOrFail($id);
            if (!$role) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No se encontro el rol'
                ], 404);
            }else {
                return response()->json([
                    'status' => 200,
                    'role' => $role
                ], 200);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id){
        if (!Auth::guard('api')->user()->can('edit roles')) {
            return response()->json([
                'status' => 403,
                'message' => 'No posee autorización para editar el rol.'
            ], 403);
        }
        try {
            $role = Role::findOrFail($id);
            if (!$role) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No se encontro el rol'
                ], 404);
            }
            $validated = $request->validate([
                'name' => 'sometimes|string|unique:roles,name,' . $id . '|max:255',
                'permissions' => 'sometimes|array',
                'permissions.*' => 'exists:permissions,name'
            ]);
            
            $role->update($validated);

            if (isset($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }
            $role->load('permissions');
            return response()->json([
                'status' => 200,
                'message' => 'Rol actualizado correctamente'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id){
        if (!Auth::guard('api')->user()->can('delete roles')) {
            return response()->json([
                'status' => 403,
                'message' => 'No esta autorizado para eliminar roles.'
            ]);
        }
        try {
            $role = Role::findOrFail($id);
            if (!$role) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No se encontro el rol'
                ], 404);
            }
            if (in_array($role->name, ['admin', 'super-admin'])) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Cannot delete system roles'
                ], 403);
            }
            $role->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Rol eliminado correctamente'
            ], 200); 

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

     public function assignPermissions(Request $request, $id)
    {
        if (!Auth::guard('api')->user()->can('assign permissions')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene autorización para asignar roles'
            ], 403);
        }
        try {
            $role = Role::findOrFail($id);
            if (!$role) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No se encontro el rol'
                ], 404);
            }
            $request->validate([
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,name'
            ]);
        
            $role->syncPermissions($request->permissions);
            $role->load('permissions');
        
            return response()->json([
                'status' => 200,
                'message' => 'Permisos asignados al rol correctamente',
                'role' => $role
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
