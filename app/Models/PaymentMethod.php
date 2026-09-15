<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentMethodFactory> */
    use HasFactory;

    protected $primaryKey = 'id_payment_method';

    protected $fillable = [
        'payment_method'
    ];

    protected $hidden = [
       'created_at',
       'updated_at'
    ];
}
