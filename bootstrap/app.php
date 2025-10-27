<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\ScrapeData;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        // Definición de ruta para el log.
        $logPath = storage_path('logs/nomina-sync.log');
        
        // Ejecuta la sincronización todos los días a las 07:00 (7 AM)
        $schedule->command('nomina:sync')
                                        ->dailyAt('07:00')
                                        ->appendOutputTo($logPath); 

        // Ejecuta la sincronización todos los días a las 02:00 (2 PM)
        $schedule->command('nomina:sync')
                                        ->dailyAt('14:00')
                                        ->appendOutputTo($logPath); 
        // Ejecuta la sincronización todos los días a las 17:00 (5 PM)
        $schedule->command('nomina:sync')
                                        ->dailyAt('17:00')
                                        ->appendOutputTo($logPath);

        $schedule->command(ScrapeData::class)
                                        ->everyThirtyMinutes()
                                        ->runInBackground();
    })
    ->create();
