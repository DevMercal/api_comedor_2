<?php

use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\ExtraController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'p1', 'namespace' => 'App\Http\Controllers'], function (){
        Route::apiResource('gerencias', ManagementController::class);
        Route::apiResource('metodosPagos', PaymentMethodController::class);
        Route::apiResource('menus', MenuController::class);
        Route::apiResource('extras', ExtraController::class);
        Route::apiResource('empleados', EmployeesController::class);
        Route::apiResource('pedidos', OrderController::class);
        Route::apiResource('users', UserController::class);
        Route::post('extras/bluk', ['uses' => 'ExtrasController@blukStore']);
        Route::post('empleados/bluk', ['uses' => 'EmpleadosController@blukStore']);
        Route::post('pedidos/bluk', ['uses' => 'PedidosController@blukStore']);
        Route::post('menus/bluk', ['uses' => 'MenusController@blukStore']);
    });