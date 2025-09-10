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
        
        User::factory()->create([
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
        ]);
        User::factory()->create([
            'name' => 'Gerencia de Salud',
            'email' => 'salud@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'id_management' => '16'
        ]);
        User::factory()->create([
            'name' => 'Atencion Al Ciudadano',
            'email' => 'atenciudadano@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'id_management' => '21'
        ]);
        User::factory()->create([
            'name' => 'Oficina de Gestion Comunicacional',
            'email' => 'ofgestioncomuni@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'id_management' => '19'
        ]);
        User::factory()->create([
            'name' => 'Oficina de Gestion Humana',
            'email' => 'ofigestionhumana@mercal.gob.ve',
            'password' => bcrypt('12345678'),
            'id_management' => '20'
        ]);
    }
}
