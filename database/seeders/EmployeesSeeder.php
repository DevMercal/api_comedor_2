<?php

namespace Database\Seeders;

use App\Models\Employees;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = File::get('database/data/nominaTecn.json');
        $data = json_decode($json, true);
        foreach ($data as $obj) {
            Employees::create([
                'first_name' => $obj['first_name'],
                'last_name' => $obj['last_name'],
                'cedula' => $obj['cedula'],
                'id_management' => $obj['id_management'],
                'state' => $obj['state'],
                'type_employee' => $obj['type_employee'],
                'position' => $obj['position']
            ]);
        }
    }
}
