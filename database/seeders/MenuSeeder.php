<?php

namespace Database\Seeders;

use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {   
        $today = Carbon::now()->toDateString();
        $MenuDayStand = [
            ['food_category' => 'Sopas', 'name_ingredient' => 'Sopa de Pollo, Sopa de Pescado',  'date_menu' => $today],
            ['food_category' => 'Contornos', 'name_ingredient' => 'Arroz, Pasta',  'date_menu' => $today],
            ['food_category' => 'Proteinas', 'name_ingredient' => 'Carne molida, Pollo',  'date_menu' => $today],
            ['food_category' => 'Ensaladas', 'name_ingredient' => 'Rayada, Rusa',  'date_menu' => $today],
            ['food_category' => 'Jugos', 'name_ingredient' => 'Papelon con Limon',  'date_menu' => $today],
            ['food_category' => 'Postre', 'name_ingredient' => 'Torta de vainilla',  'date_menu' => $today],
        ];
        $countMenuDayStand = 0;
        foreach ($MenuDayStand as $DayStand) {
            Menu::updateOrCreate([
                'food_category' => $DayStand['food_category'],
                'name_ingredient' => $DayStand['name_ingredient'],
                'date_menu' => $DayStand['date_menu']
            ]);
            $countMenuDayStand++;
        }
        $this->command->info('-------------------------------------------');
        $this->command->info(' Registro de Menu del Dia');
        $this->command->info(" Registros creados: $countMenuDayStand");
        $this->command->info('-------------------------------------------');
        /*Menu::updateOrCreate([
                'food_category' => 'Sopas',
                'name_ingredient' => 'Sopa de Pollo, Sopa de Pescado',
                'date_menu' => $today
            ]);
        Menu::updateOrCreate([
            'food_category' => 'Contornos',
            'name_ingredient' => 'Arroz, Pasta',
            'date_menu' => $today
        ]);
        Menu::updateOrCreate([
            'food_category' => 'Proteinas',
            'name_ingredient' => 'Carne molida, Pollo',
            'date_menu' => $today
        ]);
        Menu::updateOrCreate([
            'food_category' => 'Ensaladas',
            'name_ingredient' => 'Rayada, Rusa',
            'date_menu' => $today
        ]);
        Menu::updateOrCreate([
            'food_category' => 'Jugos',
            'name_ingredient' => 'Papelon con Limon',
            'date_menu' => $today
        ]);
        Menu::updateOrCreate([
            'food_category' => 'Postre',
            'name_ingredient' => 'Torta de vainilla',
            'date_menu' => $today
        ]);*/
    }
}
