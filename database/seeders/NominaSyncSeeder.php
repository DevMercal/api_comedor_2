<?php

namespace Database\Seeders;

use App\Http\Controllers\EmployeesController;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NominaSyncSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $controller = new EmployeesController();

        $response = $controller->syncNomina();

        $message = json_decode($response->getContent(), 200)['message'] ?? 'Sincronización completa.';
        $this->command->info("✅ " . $message);
    }
}
