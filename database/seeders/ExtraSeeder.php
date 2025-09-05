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
        Extra::create([
            'name_extra' => 'Envase',
            'price' => '15'
        ]);
        Extra::create([
            'name_extra' => 'Cubiertos',
            'price' => '5'
        ]);
    }
}
