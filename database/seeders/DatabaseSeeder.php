<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ManagementSeeder::class,
            OrderConsumptionSeeder::class,
            OrderStatusSeeder::class,
            PaymentMethodSeeder::class,
            MenuSeeder::class,
            NominaSyncSeeder::class,
            ExtraSeeder::class
        ]);
        
        User::factory()->create([
            'email' => 'moicastillo@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'cedula' => "18467449"
        ]);
        User::factory()->create([
            'email' => 'lnavarro@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'cedula' => '31158004'
        ]);
        User::factory()->create([
            'email' => 'danrangel@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'cedula' => '27047631'
        ]);
        User::factory()->create([
            'email' => 'pmiranda@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'cedula' => '13459347'
        ]);
    }
}
