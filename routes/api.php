<?php

use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\ExtraController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NominaController;
use App\Http\Controllers\NumberOrdersDayController;
use App\Http\Controllers\OrderConsumptionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function(){
    Route::group(['prefix' => 'p1', 'namespace' => 'App\Http\Controllers'], function (){
        Route::apiResource('gerencias', ManagementController::class);
        Route::apiResource('metodosPagos', PaymentMethodController::class);
        Route::apiResource('statusConsumption', OrderConsumptionController::class);
        Route::apiResource('statusOrder', OrderStatusController::class);
        Route::apiResource('menus', MenuController::class);
        Route::apiResource('extras', ExtraController::class);
        Route::apiResource('empleados', EmployeesController::class);
        Route::apiResource('pedidos', OrderController::class);
        Route::get('pedidos/{cedula}', [OrderController::class, 'show']);
        Route::patch('pedidos/consumo/{numberOrder}', [OrderController::class, 'consumptionOrder']);
        Route::get('pedidos/takeOrder/{id}', [OrderController::class, 'TakeOrder']);
        Route::apiResource('users', UserController::class);
        Route::apiResource('ordersDay', NumberOrdersDayController::class);
        Route::post('pedidos/bluk', ['uses' => 'OrderController@bulkStoreWithFiles']);
        Route::post('extras/bluk', ['uses' => 'ExtraController@blukStore']);
        Route::post('empleados/bluk', ['uses' => 'EmployeesController@blukStore']);
        Route::post('menus/bluk', ['uses' => 'MenuController@blukStore']);
    });
});



Route::group(['prefix' => 'p1', 'namespace' => 'App\Http\Controllers'], function (){
    Route::post('users/login', [UserController::class, 'login']);
    Route::get('nomina/employees', [NominaController::class, 'getEmployees']);
});