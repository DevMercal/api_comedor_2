<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Management extends Model
{
    /** @use HasFactory<\Database\Factories\ManagementFactory> */
    use HasFactory;

    protected $primaryKey = 'id_management';

    protected $fillable = [
        'management_name'
    ];
}
