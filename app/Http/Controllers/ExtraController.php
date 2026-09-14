<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Extra;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ExtraResource;
use App\Http\Requests\StoreExtraRequest;
use App\Http\Requests\UpdateExtraRequest;

class ExtraController extends Controller
{

    public function index()
    {
        /*if (!Auth::guard('api')->user()->can('view_extra')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permiso para visualizar los extras.'
            ]);
        }*/
        try {
            $extras = Extra::all();
            if ($extras->isEmpty()) {
                return response()->json([
                    'status' => 401,
                    'message' => 'No se encontro registros.'
                ], 401);
            }else {
                return response()->json([
                    'status' => 200,
                    'extras' => $extras
                ], 200);
            }
         } catch (Exception $e) {
            return response()->json([
                'status' => 401,
                'message' => 'Error al entrar los Extras' . $e->getMessage()
            ], 401);
        }
    }

    public function store(StoreExtraRequest $request)
    {
        /*if (!Auth::guard('api')->user()->can('create_extra')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permisos para registrar extra.'
            ]);
        }*/
        return new ExtraResource(Extra::create($request->all()));
    }

    public function show($id)
    {
        /*if (!Auth::guard('api')->user()->can('show_extra')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permisos para ver el extra.'
            ]);
        }*/
        try {
            $extra = Extra::where('id_extra', $id)->get();
            if ($extra->isEmpty()) {
                return response()->json([
                    'status' => 409,
                    'message' => 'Extra no encontrado'
                ], 409);
            }else {
                return response()->json([
                    'status' => 200,
                    'extra' => $extra
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Error al buscar extra',
                'error' => $e->getMessage()
            ]);
        }
    }
    public function update(UpdateExtraRequest $request, $id)
    {
        /*if (!Auth::guard('api')->user()->can('update_extra')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permiso para editar el extra.'
            ]);
        }*/
        try {
            $extra = Extra::where('id_extra', $id)->firstOrFail();
            $validated = $request->validated();
            $extra->update([
                'name_extra' => $validated['nameExtra'] ?? $extra->name_extra,
                'price' => $validated['price'] ?? $extra->price
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Extra actualizado correctamente',
                'extra' => $extra
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Error al actualizar extra',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    /*public function destroy($id)
    {
        $extra = Extra::where('id_extra', $id)->get();
        if ($extra->isEmpty()) {
            return response()->json([
                'status' => 409,
                'message' => 'No se encontro el extra a eliminar'
            ],409);
        }else {
            $extra->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Se elimino el usuario correctamente'
            ], 200);
        }
    }*/
}
