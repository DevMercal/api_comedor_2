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
        OrderConsumption::create([
            'orders_consumption' => 'NO APLICA'
        ]);
        OrderConsumption::create([
            'orders_consumption' => 'VALIDO'
        ]);
        OrderConsumption::create([
            'orders_consumption' => 'VENCIDO'
        ]);
    }
}
