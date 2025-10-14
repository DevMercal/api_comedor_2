<?php

namespace Database\Seeders;

use App\Models\Management;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Management::create([
            'management_name' => 'GERENCIA DE COMPRAS'
        ]);
        Management::create([
            'management_name' => 'GERENCIA DE FINANZAS'
        ]);
        Management::create([
            'management_name' => 'GERENCIA DE ADMINISTRACION'
        ]);
        Management::create([
            'management_name' => 'GERENCIA DE INFRAESTRUCTURA'
        ]);
        Management::create([
            'management_name' => 'GERENCIA DE CONTROL DE CALIDAD'
        ]);
        Management::create([
            'management_name' => 'GERENCIA GENERAL DE GESTION INSTITUCIONAL'
        ]);
        Management::create([
            'management_name' => 'GERENCIA DE GESTION SOCIALISTA'
        ]);
        Management::create([
            'management_name' => 'GERENCIA DE TRANSPORTE GENERAL'
        ]);
        Management::create([
            'management_name' => 'GERENCIA GENERAL DE GESTION ECONOMICA'
        ]);
        Management::create([
            'management_name' => 'GERENCIA DE LOGISTICA'
        ]);
        Management::create([
            'management_name' => 'GERENCIA DE SEGURIDAD INTEGRAL'
        ]);
        Management::create([
            'management_name' => 'GERENCIA DE MERCADEO Y VENTAS'
        ]);
        Management::create([
            'management_name' => 'GERENCIA GENERAL DE OPERACIONES E INSPECCION'
        ]);
        Management::create([
            'management_name' => 'GERENCIA DE PROYECTOS'
        ]);
        Management::create([
            'management_name' => 'GERENCIA DE CONTABILIDAD'
        ]);
        Management::create([
            'management_name' => 'GERENCIA DE SALUD'
        ]);
        Management::create([
            'management_name' => 'GERENCIA DE INSPECCION Y SEGUIMIENTO'
        ]);
        Management::create([
            'management_name' => 'OFICINA DE PLANIFICACION Y PRESUPUESTO'
        ]);
        Management::create([
            'management_name' => 'OFICINA DE GESTION COMUNICACIONAL'
        ]);
        Management::create([
            'management_name' => 'OFICINA DE GESTION HUMANA'
        ]);
        Management::create([
            'management_name' => 'OFICINA DE ATENCION AL CIUDADANO'
        ]);
        Management::create([
            'management_name' => 'OFICINA DE TECNOLOGIA'
        ]);
        Management::create([
            'management_name' => 'AUDITORIA INTERNA'
        ]);
        Management::create([
            'management_name' => 'CONSULTORIA JURIDICA'
        ]);
        Management::create([
            'management_name' => 'PRESIDENCIA'
        ]);
        Management::create([
            'management_name' => 'ESCUELA DE FORMACION'
        ]);
    }
}
