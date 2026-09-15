<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{

    public function run(): void
    {
        $orderStatus = [
            ['status_order' => 'NO APLICA'],
            ['status_order' => 'PENDIENTE'],
            ['status_order' => 'PAGADO'],
        ];
        $countOrderStatus = 0;

        foreach ($orderStatus as $orderstat) {
            OrderStatus::updateOrCreate([
                'status_order' => $orderstat['status_order']
            ]);
            $countOrderStatus++;
        }

        $this->command->info("-----------------------------------------------");
        $this->command->info(" Registro de estatus de orden completado.");
        $this->command->info(" Registros creados: $countOrderStatus");
        $this->command->info('-----------------------------------------------');
    }
}
