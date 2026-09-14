<?php

namespace Database\Seeders;

use App\Models\OrderConsumption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderConsumptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderConsumption::updateOrCreate([
            'orders_consumption' => 'NO APLICA'
        ]);
        OrderConsumption::updateOrCreate([
            'orders_consumption' => 'VALIDO'
        ]);
        OrderConsumption::updateOrCreate([
            'orders_consumption' => 'VENCIDO'
        ]);
    }
}
