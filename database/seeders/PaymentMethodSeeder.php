<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $paymentMethod = [
            ['payment_method' => 'EFECTIVO'],
            ['payment_method' => 'DEBITO'],
            ['payment_method' => 'PAGO MOVIL'],
            ['payment_method' => 'TRANSFERENCIA BANCARIA'],
        ];
        $countPaymentMethod = 0;

        foreach ($paymentMethod as $method) {
            PaymentMethod::updateOrCreate([
                'payment_method' => $method['payment_method']
            ]);
            $countPaymentMethod++;
        }
        $this->command->info('-----------------------------------------');
        $this->command->info(" Registro de metodos de pago completado.");
        $this->command->info(" Registros Creados: $countPaymentMethod");
        $this->command->info('-----------------------------------------');
    }
}
