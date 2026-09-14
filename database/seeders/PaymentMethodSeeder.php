<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::updateOrCreate([
            'payment_method' => 'Efectivo'
        ]);
        PaymentMethod::updateOrCreate([
            'payment_method' => 'Debito'
        ]);
        PaymentMethod::updateOrCreate([
            'payment_method' => 'Pago Móvil'
        ]);
        PaymentMethod::updateOrCreate([
            'payment_method' => 'Transferencia Bancaria'
        ]);
    }
}
