<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Employees extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeesFactory> */
    use HasFactory;
    
    protected $primaryKey = 'id_employee';

    protected $fillable = [
        'firt_name',
        'last_name',
        'cedula',
        'id_management',
        'state',
        'type_employee',
        'position'
    ];

    protected $hidden = [
        'id_management', 
    ];



    public function gerencias() : BelongsTo
    {
        return $this->belongsTo(Management::class, 'id_management', 'id_management');
    }

}
