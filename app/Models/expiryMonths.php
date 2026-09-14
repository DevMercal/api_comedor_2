<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expiryMonths extends Model
{
    /** @use HasFactory<\Database\Factories\ExpiryMonthsFactory> */
    use HasFactory;

    protected $primaryKey = 'id_expiry_month';

    protected $fillable = [
        'months',
        'description'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
