<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $userData = [
            [
                'email' => 'moicastillo@mercal.gob.ve',
                'username' => 'moicastillo', 
                'password' => '12345678', 
                'cedula' => '18467449', 
                'id_time_token' => '5', 
                'id_expiry_month' => '3', 
                'is_active' => '1', 
                'role' => "Admin"
            ],
            [
                'email' => 'danrangel@mercal.gob.ve',
                'username' => 'danrangel', 
                'password' => '12345678', 
                'cedula' => '27047631', 
                'id_time_token' => '5', 
                'id_expiry_month' => '3', 
                'is_active' => '1', 
                'role' => "Admin"
            ],
            [
                'email' => 'kprieto@mercal.gob.ve',
                'username' => 'kprieto', 
                'password' => '12345678', 
                'cedula' => '20327830', 
                'id_time_token' => '5', 
                'id_expiry_month' => '3', 
                'is_active' => '1', 
                'role' => "Admin"
            ],
            [
                'email' => 'arangel@mercal.gob.ve',
                'username' => 'arangel', 
                'password' => '12345678', 
                'cedula' => '22036006', 
                'id_time_token' => '5', 
                'id_expiry_month' => '3', 
                'is_active' => '1', 
                'role' => "Admin"
            ],
        ]; 

        foreach ($userData as $user) {
            $user = User::updateOrCreate(
                [
                    'email' => $user['email'],
                    'cedula' => $user['cedula'],
                    'username' => $user['username'],
                    'password' => bcrypt($user['password']),
                    'id_time_token' => $user['id_time_token'],
                    'id_expiry_month' => $user['id_expiry_month'],
                    'is_active' => $user['is_active']
                ],
            );
            $user->syncRoles([$user['role']]);
        }
    }
}
