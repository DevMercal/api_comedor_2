<?php

namespace Database\Seeders;

use App\Models\Extra;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExtraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Extra::updateOrCreate([
            'name_extra' => 'No Aplica',
            'price' => '0'
        ]);
        Extra::updateOrCreate([
            'name_extra' => 'Envase',
            'price' => '15'
        ]);
        Extra::updateOrCreate([
            'name_extra' => 'Cubiertos',
            'price' => '5'
        ]);
    }
}
