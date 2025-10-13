<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class NominaController extends Controller
{
    //
    public function getEmployees(){
        $nomina = DB::connection('pgsql')
                         ->select("SELECT codemp, nomemp, nomcar, sexemp, fecing, cod_estado, estado, codniv, unidad_adm, codnom, nomina FROM \"SIMA001\".wv_trabajadores_sede");
        return response()->json([
            'status' => 200,
            'nomina' => $nomina
        ]);

    }
}
