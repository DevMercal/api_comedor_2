<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            TimeTokensSeeder::class,
            ExpiryMonthsSeeder::class,
            ManagementSeeder::class,
            OrderConsumptionSeeder::class,
            OrderStatusSeeder::class,
            PaymentMethodSeeder::class,
            MenuSeeder::class,
            NominaSyncSeeder::class,
            ExtraSeeder::class,
            BancosSeeder::class,
            RolesAndPermission::class,
            UserSeeder::class
        ]);
    }
}
