<?php

namespace Database\Seeders;

use App\Models\Extra;
use Illuminate\Database\Seeder;

class ExtraSeeder extends Seeder
{
    public function run(): void
    {
        $Extras = [
            ['name_extra' => 'NO APLICA', 'price' => 0],
            ['name_extra' => 'ENVASE', 'price' => 800],
            ['name_extra' => 'CUBIERTOS', 'price' => 200],
        ];
        $countExtras = 0;
        foreach ($Extras as $e) {
            Extra::updateOrCreate([
                'name_extra' => $e['name_extra'],
                'price' => $e['price']
            ]);
            $countExtras++;
        }
        $this->command->info('------------------------------------------------');
        $this->command->info(' Registro de extras completado.');
        $this->command->info(" Registros creados: $countExtras");
        $this->command->info('------------------------------------------------');
    }
}
