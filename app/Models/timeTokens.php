<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class timeTokens extends Model
{
    /** @use HasFactory<\Database\Factories\TimeTokensFactory> */
    use HasFactory;

    protected $primaryKey = 'id_time_token';

    protected $fillable = [
        'time_token',
        'description'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
