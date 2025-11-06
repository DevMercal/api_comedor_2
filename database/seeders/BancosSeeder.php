<?php

namespace Database\Seeders;

use App\Models\Bancos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BancosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = File::get('database/data/bankData.json');
        $data = json_decode($json, true);
        foreach ($data as $obj) {
            Bancos::create([
                'code_bank' => $obj['code_bank'],
                'name_bank' => $obj['name_bank']
            ]);
        }
    }
}
