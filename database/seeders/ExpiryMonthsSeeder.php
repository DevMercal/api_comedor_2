<?php

namespace Database\Seeders;

use App\Models\expiryMonths;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpiryMonthsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $expiryData = [
            ['monts' => '3', 'description' => 'Expiración de clave 3 Meses'],
            ['monts' => '6', 'description' => 'Expiración de clave 6 Meses'],
            ['monts' => '12', 'description' => 'Expiración de clave 12 Meses']
        ];

        foreach ($expiryData as $data) {
            expiryMonths::create([
                    'monts' => $data['monts'],
                    'description' => $data['description']
            ]);
        }
    }
}
