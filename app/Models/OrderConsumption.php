<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderConsumption extends Model
{
    /** @use HasFactory<\Database\Factories\OrderConsumptionFactory> */
    use HasFactory;

    protected $primaryKey = 'id_orders_consumption';

    protected $fillable = [
        'status_order'
    ];

    protected $hidden = [
       'created_at',
       'updated_at'
    ];
}
