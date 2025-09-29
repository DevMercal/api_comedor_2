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
            EmployeesSeeder::class,
            ExtraSeeder::class
        ]);
        
        /*User::factory()->create([
            'name' => 'Moises Castillo',
            'email' => 'moicastillo@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'id_management' => '22'
        ]);
        User::factory()->create([
            'name' => 'Luis Navarro',
            'email' => 'lnavarro@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'id_management' => '22'
        ]);
        User::factory()->create([
            'name' => 'Danyerbert Rangel',
            'email' => 'danrangel@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'id_management' => '22'
        ]);*/
        User::factory()->create([
            'email' => 'tecnologia@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'cedula' => '12345678',
            'id_management' => '22'
        ]);
        User::factory()->create([
            'email' => 'salud@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'cedula' => '12345679',
            'id_management' => '16'
        ]);
        User::factory()->create([
            'email' => 'atenciudadano@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'cedula' => '12345677',
            'id_management' => '21'
        ]);
        User::factory()->create([
            'email' => 'ofigestionhumana@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'cedula' => '12345676',
            'id_management' => '20'
        ]);
        User::factory()->create([
            'email' => 'ofgestioncomuni@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'cedula' => '12345675',
            'id_management' => '19'
        ]);
    }
}
