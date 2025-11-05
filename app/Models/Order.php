<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'authorized',
        'authorized_person',
        'id_payment_method',
        'reference',
        'total_amount',
        'cedula',
        'id_order_status',
        'id_orders_consumption',
        'date_order',
        'payment_support'
    ];
    protected $hidden = [
       'cedula',
       'id_order_status',
       'id_orders_consumption',
       'id_payment_method'
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
    public function employees() : BelongsTo
    {
        return $this->belongsTo(Employees::class, 'cedula', 'cedula');
    }
    public function orderStatus(): BelongsTo {
        return $this->belongsTo(OrderStatus::class, 'id_order_status' , 'id_order_status');
    }
    public function orderConsumption() : BelongsTo {
        return $this->belongsTo(OrderConsumption::class, 'id_orders_consumption', 'id_orders_consumption');
    }
    public function paymentMethod(): BelongsTo {
        return $this->belongsTo(PaymentMethod::class, 'id_payment_method', 'id_payment_method');
    }
}
