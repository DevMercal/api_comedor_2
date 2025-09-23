<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class numberOrdersDay extends Model
{
    /** @use HasFactory<\Database\Factories\NumberOrdersDayFactory> */
    use HasFactory;


    protected $fillable = [
        'numbers_orders_day',
        'date_number_orders'
    ];

}
