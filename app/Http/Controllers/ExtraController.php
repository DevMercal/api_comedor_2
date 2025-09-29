<?php

namespace App\Http\Controllers;

use App\Models\Extra;
use App\Http\Requests\StoreExtraRequest;
use App\Http\Requests\UpdateExtraRequest;
use App\Http\Resources\ExtraResource;

class ExtraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
         } catch (\Exception $e) {
            return response()->json([
                'status' => 401,
                'message' => 'Error al entrar los Extras' . $e->getMessage()
            ], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExtraRequest $request)
    {
        //
        return new ExtraResource(Extra::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Error al buscar extra',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExtraRequest $request, $id)
    {
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Error al actualizar extra',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Extra $extra)
    {
        //
    }
}
