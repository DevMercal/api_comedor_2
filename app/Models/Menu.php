<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /** @use HasFactory<\Database\Factories\MenuFactory> */
    use HasFactory;

    protected $table = 'menus';
    
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'food_category',
        'name_ingredient',
        'date_menu'
    ];

    // 4. Casteo de fecha
    protected $casts = [
        'date_menu' => 'date:Y-m-d',
    ];
}
