<?php

namespace Database\Seeders;

use App\Models\Bancos;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BancosSeeder extends Seeder
{

    public function run(): void
    {
        $countBank = 0;
        $json = File::get('database/data/bankData.json');
        $data = json_decode($json, true);
        foreach ($data as $obj) {
            Bancos::updateOrCreate([
                'code_bank' => $obj['code_bank'],
                'name_bank' => $obj['name_bank']
            ]);
            $countBank++;
        }
        $this->command->info('----------------------------------------');
        $this->command->info(' Registro de bancos completados.');
        $this->command->info(" Registros creados: $countBank");
        $this->command->info('---------------------------------------');
    }
}
