<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Management extends Model
{
    /** @use HasFactory<\Database\Factories\ManagementFactory> */
    use HasFactory;

    protected $fillable = [
        'management_name'
    ];
}
