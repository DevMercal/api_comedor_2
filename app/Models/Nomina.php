<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    //
    protected $connection = 'pgsql';

    protected $table = 'SIMA001.wv_trabajadores_sede';

    public $timestamps = false;

    // Indica que los campos se pueden leer de la vista
    protected $fillable = [
        'codemp', 'nomemp', 'nomcar', 'sexemp', 'fecing', 'cod_estado', 
        'estado', 'codniv', 'unidad_adm', 'codnom', 'nomina'
    ];
}
