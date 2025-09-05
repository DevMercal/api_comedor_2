<?php

namespace App\Http\Controllers;

use App\Models\Management;
use App\Http\Requests\StoreManagementRequest;
use App\Http\Requests\UpdateManagementRequest;

class ManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $management = Management::all();
            return response()->json([
                'status' => 200,
                $management
            ], 200);    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404, 
                'message' => 'Error en obtener los datos' . $e->getMessage()
            ], 404);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreManagementRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $management = Management::where('id_management', $id)->first();
            return response()->json([
                'status' => 200,
                $management
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Gerencia no encontrada' . $e->getMessage()
            ]);
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateManagementRequest $request, Management $management)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $management = Management::where('id_management', $id)->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Gerencia eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 401,
                'message' => 'Error al encontrar Gerencia' . $e->getMessage()
            ], 401);
        }
    }
}
