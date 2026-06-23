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
        $controller = new EmployeesController();
        $response = $controller->syncNomina();
        
        // Decodificamos correctamente pasándole true
        $data = json_decode($response->getContent(), true);
        
        if (isset($data['error']) || isset($data['errores'])) {
            $errorMsg = $data['error'] ?? $data['errores'];
            $this->command->error("❌ Error en la sincronización: " . $errorMsg);
        } else {
            $total = $data['total_registros_sincronizados'] ?? 0;
            $this->command->info("✔ Sincronización completa. Registros afectados: " . $total);
        }
    }
}
