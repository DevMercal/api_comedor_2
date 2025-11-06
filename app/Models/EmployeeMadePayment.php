<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeMadePayment extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeMadePaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'cedula_employee',
        'code_bank',
        'phone_employee',
        'id_order'
    ];

    protected $hidden = [
       'id_order',
       'code_bank',
       'created_at',
       'updated_at'
    ];

    public function order(){
        return $this->belongsTo(Order::class, 'id_order', 'number_order');
    }

    public function bank(){
        return $this->belongsTo(Bancos::class, 'code_bank', 'code_bank');
    }

    public function employee() : BelongsTo
    {
        return $this->belongsTo(Employees::class, 'cedula_employee', 'cedula');
    }
}
