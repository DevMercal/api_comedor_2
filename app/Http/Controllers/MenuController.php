<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\BlukStoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Resources\MenuResource;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class MenuController extends Controller
{

    public function index()
    {
        try {
            // Formatear a '2026-09-11' directamente
            $today = Carbon::today()->format('Y-m-d');

            // Realizar la búsqueda por fecha
            $menu = Menu::whereDate('date_menu', $today)->get();

            if ($menu->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No hay carga de Menu'
                ], 200);
            }

            return response()->json([
                'success' => true,
                'menus' => $menu
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Error en la consulta: ' . $e->getMessage()
            ], 404);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request)
    {
        return new MenuResource(Menu::create($request->all()));
    }

    public function BlukStore(BlukStoreMenuRequest $request)
    {
        $dataCheks = Carbon::today()->toDateString();
        $menusExists = Menu::where('date_menu', $dataCheks)->exists();

        if ($menusExists) {
            return response()->json([
                'status'  => 409,
                'message' => 'El MENU ya fue cargado'
            ], 409);
        }

        // Obtener los datos validados del FormRequest
        $items = $request->validated()['menus'];

        // Si la validación no devuelve la lista plana, se lee directamente el payload
        if (empty($items)) {
            $items = json_decode($request->getContent(), true) ?? [];
        }

        $now = Carbon::now();
        $dataToInsert = [];

        foreach ($items as $item) {
            if (isset($item['foodCategory']) && isset($item['ingredient'])) {
                $dataToInsert[] = [
                    'food_category'   => $item['foodCategory'],
                    'name_ingredient' => $item['ingredient'],
                    'date_menu'       => $dataCheks,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
            }
        }

        if (empty($dataToInsert)) {
            return response()->json([
                'status'  => 400,
                'message' => 'Estructura JSON inválida o vacía'
            ], 400);
        }

        Menu::insert($dataToInsert);

        return response()->json([
            'status'  => 200,
            'message' => 'Menu guardado Correctamente'
        ], 200);
    }
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
    public function update(UpdateMenuRequest $request, $id)
    {
        try {
            $menu = Menu::where('id_menu', $id)->firstOrFail();
            $validated = $request->validated();
            $menu->update([
                'food_category' => $validated['foodCategory'] ?? $menu->food_category,
                'name_ingredient' => $validated['ingredient'] ?? $menu->name_ingredient,
                'date_menu' => $validated['dateMenu'] ?? $menu->date_menu
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Se actualizo el item correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 409,
                'message' => 'Error al actualizar el item',
                'error' => $e->getMessage()
            ], 409);
        }
    }

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
