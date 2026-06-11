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
            TimeTokensSeeder::class,
            ExpiryMonthsSeeder::class,
            ManagementSeeder::class,
            OrderConsumptionSeeder::class,
            OrderStatusSeeder::class,
            PaymentMethodSeeder::class,
            MenuSeeder::class,
            NominaSyncSeeder::class,
            ExtraSeeder::class,
            BancosSeeder::class
        ]);
        
        /*$userData = [
            ['email' => 'moicastillo@mercal.gob.ve', 'password' => '12345678', 'cedula' => '18467449', 'id_time_token' => '5', 'id_expiry_month' => '3', 'is_active' => '1'],
            ['email' => 'danrangel@mercal.gob.ve', 'password' => '12345678', 'cedula' => '27047631', 'id_time_token' => '5', 'id_expiry_month' => '3', 'is_active' => '1'],
            ['email' => 'kleinysp@mercal.gob.ve', 'password' => '12345678', 'cedula' => '20327830', 'id_time_token' => '5', 'id_expiry_month' => '3', 'is_active' => '1'],
            ['email' => 'artrangel@mercal.gob.ve', 'password' => '12345678', 'cedula' => '22036006', 'id_time_token' => '5', 'id_expiry_month' => '3', 'is_active' => '1'],
        ];

        foreach ($userData as $data) {
            User::create([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'cedula' => $data['cedula'],
                'id_time_token' => $data['id_time_token'],
                'id_expiry_month' => $data['id_expiry_month'],
                'is_active' => $data['is_active'] 
            ]);
        }
            */
    }
}
