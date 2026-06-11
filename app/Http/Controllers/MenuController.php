<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\BlukStoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Resources\MenuResource;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Exception;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    
    public function index()
    {
        if (!Auth::guard('api')->user()->can('view_menu')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permiso para visualizar el menu.'
            ], 403);
        }
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
        } catch (Exception $e) {
            return response()->json([
                    'status' => 404,
                    'message' => 'No se encontraron registros' . $e->getMessage()  
                ], 404);
        }

    }

    public function store(StoreMenuRequest $request)
    {
        if (!Auth::guard('api')->user()->can('create_menu')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permiso para guardar menu.'
            ], 403);
        }
        return new MenuResource(Menu::create($request->all()));
    }

    public function BlukStore(BlukStoreMenuRequest $request){
        if (!Auth::guard('api')->user()->can('create_menu_bluk')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permiso de cargar menu.'
            ], 403);
        }
        try {
            $dataCheks = Carbon::now()->toDateString();
            $menusExists = Menu::where('date_menu', $dataCheks)->exists();

            if ($menusExists) {
                return response()->json([
                    'status' => 409,
                    'message' => 'El MENU ya fue cargado'
                ], 409);
            }else {
                $bluk = collect($request->all())->map(function ($arr, $key){
                    return Arr::except($arr, ['foodCategory', 'ingredient', 'dateMenu']);
                });
                Menu::insert($bluk->toArray());
                return response()->json([
                    'status' => 200,
                    'message' => "Menu guardado Correctamente"
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error en registrar menu: '. $e->getMessage()
            ]);
        }
    }
    public function show($date)
    {
        if (!Auth::guard('api')->user()->can('show_menu')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permiso para visualizar el menu.'
            ], 403);
        }
        try {
            $menu = Menu::whereDate('date_menu', $date)->get();
            return response()->json([
                'status' => 200,
                $menu
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 401,
                'message' => 'Error en encontrar Menu' . $e->getMessage() 
            ]);
        }
    }

    public function update(UpdateMenuRequest $request, $id)
    {
        if (!Auth::guard('api')->user()->can('update_menu')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permiso para editar el menu.'
            ], 403);
        }
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
        } catch (Exception $e) {
            return response()->json([
                'status' => 409,
                'message' => 'Error al actualizar el item',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function destroy($date)
    {
        if (!Auth::guard('api')->user()->can('delete_menu')) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene permiso para eliminar el menu.'
            ], 403);
        }
        try {
            Menu::whereDate('date_menu', $date)->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Menu eliminado Correctamente'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 401,
                'message' => 'Error al eliminar Menu'. $e->getMessage() 
            ], 401);
        }
    }
}
