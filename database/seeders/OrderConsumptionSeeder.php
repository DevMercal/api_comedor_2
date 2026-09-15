<?php

namespace Database\Seeders;

use App\Models\OrderConsumption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderConsumptionSeeder extends Seeder
{

    public function run(): void
    {
        $orderConsumption = [
            ['orders_consumption' => 'NO APLICA'],
            ['orders_consumption' => 'VALIDO'],
            ['orders_consumption' => 'VENCIDO'],
        ];
        $countOrderConsumtion = 0;

        foreach ($orderConsumption as $consumtion) {
            OrderConsumption::updateOrCreate([
                'orders_consumption' => $consumtion['orders_consumption']
            ]);
            $countOrderConsumtion++;
        }
        $this->command->info('--------------------------------------------');
        $this->command->info(" Registro de estatus de consumo completado.");
        $this->command->info(" Registros creados: $countOrderConsumtion");
        $this->command->info('---------------------------------------------');
    }
}
