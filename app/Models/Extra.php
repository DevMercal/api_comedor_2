<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    /** @use HasFactory<\Database\Factories\ExtraFactory> */
    use HasFactory;

    protected $fillable = [
        'name_extra',
        'price'
    ];

    protected $primaryKey = 'id_extra'; 
    
    protected $hidden = [
       'created_at',
       'updated_at'
    ];

    public function orders(){
        return $this->belongsToMany(Order::class, 'order_extras', 'id_extra', 'id_order');
    }
}
