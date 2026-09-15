<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Employees extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeesFactory> */
    use HasFactory, Auditable;
    
    protected $primaryKey = 'cedula';

    protected $fillable = [
        'first_name',
        'last_name',
        'cedula',
        'management',
        'state',
        'type_employee',
        'position',
        'phone'
    ];

    protected $hidden = [
       'created_at',
       'updated_at'
    ];
    

}
