<?php

namespace Database\Seeders;

use App\Models\Employees;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class EmployeesSeeder extends Seeder
{

    public function run(): void
    {   
        $countEmployees = 0;
        $json = File::get('database/data/nominapruebas.json');
        $data = json_decode($json, true);
        foreach ($data as $obj) {
            Employees::updateOrCreate([
                'cedula' => $obj['cedula'],
                'first_name' => $obj['first_name'],
                'last_name' => $obj['last_name'],
                'management' => $obj['management'],
                'state' => $obj['state'],
                'type_employee' => $obj['type_employee'],
                'position' => $obj['position'],
                'phone' => $obj['phone']
            ]);
            $countEmployees++;
        }
        $this->command->info('-----------------------------------------------');
        $this->command->info(' Registro de empleados completado.');
        $this->command->info(" Registros creados: $countEmployees");
        $this->command->info('-----------------------------------------------');
    }
}
