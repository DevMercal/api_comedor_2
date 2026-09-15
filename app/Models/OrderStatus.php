<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    /** @use HasFactory<\Database\Factories\OrderStatusFactory> */
    use HasFactory;

    protected $primaryKey = 'id_order_status';

    protected $fillable = [
        'status_order'
    ];

    protected $hidden = [
       'created_at',
       'updated_at'
    ];
}
