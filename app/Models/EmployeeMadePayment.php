<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeMadePayment extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeMadePaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'cedula_employee',
        'name_employee',
        'phone_employee',
        'management',
        'id_order'
    ];
}
