<?php

namespace Database\Seeders;

use App\Models\expiryMonths;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpiryMonthsSeeder extends Seeder
{
    public function run(): void
    {
        $expiryData = [
            ['monts' => '3', 'description' => 'Expiración de clave 3 Meses'],
            ['monts' => '6', 'description' => 'Expiración de clave 6 Meses'],
            ['monts' => '12', 'description' => 'Expiración de clave 12 Meses']
        ];
        $countExpiryMonth = 0;
        foreach ($expiryData as $data) {
            expiryMonths::updateOrCreate([
                'monts' => $data['monts'],
                'description' => $data['description']
            ]);
            $countExpiryMonth++;
        }
        $this->command->info('----------------------------------------------');
        $this->command->info(' Registro de tiempo de expiracion completado.');
        $this->command->info(" Registros creados: $countExpiryMonth");
        $this->command->info('----------------------------------------------');
    }
}
