<?php

namespace Database\Seeders;

use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $today = Carbon::now()->toDateString();
        Menu::create([
                'food_category' => 'Sopas',
                'name_ingredient' => 'Sopa de Pollo, Sopa de Pescado',
                'date_menu' => $today
            ]);
        Menu::create([
            'food_category' => 'Contornos',
            'name_ingredient' => 'Arroz, Pasta',
            'date_menu' => $today
        ]);
        Menu::create([
            'food_category' => 'Proteinas',
            'name_ingredient' => 'Carne molida, Pollo',
            'date_menu' => $today
        ]);
        Menu::create([
            'food_category' => 'Ensaladas',
            'name_ingredient' => 'Rayada, Rusa',
            'date_menu' => $today
        ]);
        Menu::create([
            'food_category' => 'Jugos',
            'name_ingredient' => 'Papelon con Limon',
            'date_menu' => $today
        ]);
        Menu::create([
            'food_category' => 'Postre',
            'name_ingredient' => 'Torta de vainilla',
            'date_menu' => $today
        ]);
    }
}
