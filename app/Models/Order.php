<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number_order',
        'special_event',
        'authorized_person',
        'id_payment_method',
        'reference',
        'total_amount',
        'id_menu',
        'id_employee',
        'id_order_status',
        'id_orders_consumption',
        'date_order',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'number_order';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public function employeePayment(){
        return $this->hasOne(EmployeeMadePayment::class, 'id_order', 'number_order');
    }
    public function extras(){
        return $this->belongsToMany(Extra::class, 'order_extras', 'id_order', 'id_extra');
    }
}
