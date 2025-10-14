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
        PaymentMethod::create([
            'payment_method' => 'Efectivo Bolivares'
        ]);
        PaymentMethod::create([
            'payment_method' => 'Efectivo Divisas'
        ]);
        PaymentMethod::create([
            'payment_method' => 'Pago Móvil'
        ]);
        PaymentMethod::create([
            'payment_method' => 'Transferencia Bancaria'
        ]);
    }
}
