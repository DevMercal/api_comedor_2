<?php

namespace App\Console\Commands;

use App\Models\ExchangeRate; // Importar el modelo
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeData extends Command
{
    protected $signature = 'scrape:bcv'; // name_bank más específico
    protected $description = 'Scrape daily USD rate from BCV and save to DB';

    public function handle(): int
    {
        $url = 'https://www.bcv.org.ve/';
        
        try {
            $response = Http::withOptions([
                'verify' => false, // <--- DESACTIVAR LA VERIFICACIÓN SSL
            ])->get($url);
            // 1. Realizar la solicitud HTTP
            //$response = Http::get($url);
            
            if (!$response->successful()) {
                $this->error("Error de conexión. code_bank: " . $response->status());
                return Command::FAILURE;
            }
            
            $htmlContent = $response->body();
            
            // 2. Inicializar el Crawler
            $crawler = new Crawler($htmlContent, $url);

            // 3. ENCONTRAR EL SELECTOR: El valor del dólar suele estar en la sección principal.
            // NOTA: Este selector debe ser verificado en el HTML actual de la página del BCV.
            // Se asume que está en un elemento de tarjeta con una clase específica.
            $selector = '#dolar'; 
            
            $rateNode = $crawler->filter($selector . ' strong')->first(); 
            
            if ($rateNode->count() > 0) {
                $rawRate = $rateNode->text();
                
                // Limpieza de datos: El BCV usa la COMA (,) como separador decimal.
                // 1. Eliminar separadores de miles (puntos).
                // 2. Reemplazar la coma decimal por un punto decimal.
                $cleanedRate = str_replace('.', '', $rawRate); 
                $finalRate = str_replace(',', '.', $cleanedRate);
                
                $finalRate = (float) $finalRate;
                $today = now()->toDateString();
                
                // 4. Guardar en la Base de Datos
                ExchangeRate::create([
                    'currency' => 'USD',
                    'rate' => $finalRate,
                    'date' => $today,
                ]);

                $this->info("✅ Tasa del dólar BCV ({$finalRate} Bs) guardada para {$today}.");
                
            } else {
                $this->error("❌ No se encontró el elemento para la tasa con el selector: '{$selector}'.");
                $this->warn("El contenido puede ser dinámico (JavaScript). Verifica el selector o usa Laravel Dusk.");
                return Command::FAILURE;
            }

        } catch (\Illuminate\Database\QueryException $e) {
            // Manejar error si ya existe el registro para hoy (debido a la clave unique)
            if ($e->getCode() == 23000) { 
                $this->warn("⚠️ La tasa del dólar para hoy ({$today}) ya existe. Registro omitido.");
                return Command::SUCCESS;
            }
            $this->error("Error al guardar en la DB: " . $e->getMessage());
            return Command::FAILURE;
        } catch (\Exception $e) {
            $this->error("Error general de scraping: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}