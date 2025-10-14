<?php

namespace App\Console\Commands;

use App\Http\Controllers\EmployeesController;
use Illuminate\Console\Command;

class SyncNominaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nomina:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza los datos de la nómina desde la vista de PostgreSQL a la BD MySQL.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Iniciando sincronización de nómina...');
        try {
            $controller = new EmployeesController();
            $response = $controller->syncNomina();
            $content = json_decode($response->getContent(), 200);
            if ($content['status']) {
                $this->info('Exito: ' .$content['message']);
                $this->info('Total Registros sincronizados: ' . $content['total_registros_sincronizados']);
            }else {
                $this->error("Fallo: " . $content['message']);
            }
        } catch (\Exception $e) {
            $this->error('Ocurrio un error inesperado durante la sincronización: '. $e->getMessage());
            return 1;
        }
        return 0;
    }
}
