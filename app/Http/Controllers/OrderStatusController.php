<?php

namespace App\Http\Controllers;

use App\Models\OrderStatus;
use App\Http\Requests\StoreOrderStatusRequest;
use App\Http\Requests\UpdateOrderStatusRequest;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderStatus = OrderStatus::all();
        if ($orderStatus->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No se encontraron registros.'
            ], 404);
        }
        return response()->json([
            'status' => 200,
            'statusOrder' => $orderStatus
        ], 200);
    }
}
