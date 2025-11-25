<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bancos extends Model
{
    /** @use HasFactory<\Database\Factories\BancosFactory> */
    use HasFactory;

    public $incrementing = false;

    protected $primaryKey = 'code_bank';

    protected $fillable = [
        'code_bank',
        'name_bank'
    ];

    protected $hidden = [
       'created_at',
       'updated_at'
    ];

}
