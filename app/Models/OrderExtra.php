<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderExtra extends Model
{
    protected $fillable = [
        'id_order',
        'id_extra'
    ];
}
