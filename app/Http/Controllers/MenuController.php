<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use Carbon\Carbon;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $today = Carbon::now();
            $menu = Menu::whereDate('date_menu', $today)->get();
            if ($menu->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No hay carga de Menu'  
                ], 200);
            }else {
                return response()->json([
                    'success' => true,
                    'menus' => $menu->toArray() 
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                    'status' => 401,
                    'message' => 'No se encontraron registros' . $e->getMessage()  
                ], 401);
        }

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($date)
    {
        try {
            $menu = Menu::whereDate('date_menu', $date)->get();
            return response()->json([
                'status' => 200,
                $menu
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 401,
                'message' => 'Error en encontrar Menu' . $e->getMessage() 
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($date)
    {
        try {
            Menu::whereDate('date_menu', $date)->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Menu eliminado Correctamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 401,
                'message' => 'Error al eliminar Menu'. $e->getMessage() 
            ], 401);
        }
    }
}
