<?php

namespace Database\Seeders;

use App\Models\timeTokens;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeTokensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $timeTokenData = [
            ['time_token' => 5, 'description' => '5 Minutos', 'activo' => 1],
            ['time_token' => 10, 'description' => '10 Minutos', 'activo' => 1],
            ['time_token' => 15, 'description' => '15 Minutos', 'activo' => 1],
            ['time_token' => 30, 'description' => '30 Minutos', 'activo' => 1],
            ['time_token' => 60, 'description' => '60 Minutos', 'activo' => 1],
        ];

        foreach ($timeTokenData as $data) {
            timeTokens::create([
                'time_token' => $data['time_token'],
                'description' => $data['description'],
                'activo' => $data['activo']
            ]);
        }
    }
}
