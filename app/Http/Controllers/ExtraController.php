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
    public function show(Extra $extra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExtraRequest $request, Extra $extra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Extra $extra)
    {
        //
    }
}
