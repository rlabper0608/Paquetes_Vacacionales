<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Vacacion;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VacacionSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = DB::table('tipo')->pluck('id', 'nombre')->toArray();
        $accessKey = env('UNSPLASH_ACCESS_KEY');

        $destinos = [
            ['titulo' => 'Maldivas Luxury', 'tipo' => 'Playa', 'precio' => 1500],
            ['titulo' => 'Aventura en los Alpes', 'tipo' => 'Montaña', 'precio' => 850],
            ['titulo' => 'Roma Histórica', 'tipo' => 'Cultural', 'precio' => 600],
            ['titulo' => 'Safari en Kenia', 'tipo' => 'Aventura', 'precio' => 2100],
            ['titulo' => 'Crucero por el Caribe', 'tipo' => 'Crucero', 'precio' => 1100],
            ['titulo' => 'Luces de Tokio', 'tipo' => 'Ciudad', 'precio' => 1800],
            ['titulo' => 'Ibiza Party & Relax', 'tipo' => 'Playa', 'precio' => 450],
            ['titulo' => 'Esquí en Aspen', 'tipo' => 'Montaña', 'precio' => 2500],
            ['titulo' => 'Atenas y las Islas', 'tipo' => 'Cultural', 'precio' => 900],
            ['titulo' => 'Selva Amazónica', 'tipo' => 'Aventura', 'precio' => 1300],
            ['titulo' => 'Fiordos Noruegos', 'tipo' => 'Crucero', 'precio' => 1600],
            ['titulo' => 'Nueva York Express', 'tipo' => 'Ciudad', 'precio' => 1400],
            ['titulo' => 'Bali Espiritual', 'tipo' => 'Playa', 'precio' => 950],
            ['titulo' => 'Pirineos Mágicos', 'tipo' => 'Montaña', 'precio' => 300],
            ['titulo' => 'Praga Bohemio', 'tipo' => 'Cultural', 'precio' => 400],
            ['titulo' => 'Ruta 66 USA', 'tipo' => 'Aventura', 'precio' => 2200],
            ['titulo' => 'Crucero Mediterráneo', 'tipo' => 'Crucero', 'precio' => 800],
            ['titulo' => 'Londres Clásico', 'tipo' => 'Ciudad', 'precio' => 550],
            ['titulo' => 'Caribe Dominicano', 'tipo' => 'Playa', 'precio' => 700],
            ['titulo' => 'Andes Chilenos', 'tipo' => 'Montaña', 'precio' => 1200],
            ['titulo' => 'Egipto y el Nilo', 'tipo' => 'Cultural', 'precio' => 1100],
            ['titulo' => 'Desierto del Sahara', 'tipo' => 'Aventura', 'precio' => 750],
            ['titulo' => 'Nilo en Faluca', 'tipo' => 'Crucero', 'precio' => 450],
            ['titulo' => 'Berlín Alternativo', 'tipo' => 'Ciudad', 'precio' => 380],
            ['titulo' => 'Costa Brava', 'tipo' => 'Playa', 'precio' => 250],
            ['titulo' => 'Sierra Nevada', 'tipo' => 'Montaña', 'precio' => 180],
            ['titulo' => 'París Romántico', 'tipo' => 'Cultural', 'precio' => 700],
            ['titulo' => 'Gran Cañón Arizona', 'tipo' => 'Aventura', 'precio' => 900],
            ['titulo' => 'Crucero por el Báltico', 'tipo' => 'Crucero', 'precio' => 1300],
            ['titulo' => 'Sidney y la Ópera', 'tipo' => 'Ciudad', 'precio' => 2400],
            ['titulo' => 'Islas Griegas', 'tipo' => 'Playa', 'precio' => 850],
            ['titulo' => 'Everest Base Camp', 'tipo' => 'Aventura', 'precio' => 3500],
        ];
        
        foreach ($destinos as $destino) {
            // 1. Crear el paquete vacacional
            $vacacion = Vacacion::create([
                'titulo'      => $destino['titulo'],
                'descripcion' => 'Una experiencia increíble en ' . $destino['titulo'] . '.',
                'precio'      => $destino['precio'],
                'idtipo'      => $tipos[$destino['tipo']] ?? array_rand($tipos),
            ]);

            // 2. Pedir 3 fotos diferentes para alimentar el carrusel
            $response = Http::get('https://api.unsplash.com/search/photos', [
                'query' => $destino['titulo'],
                'client_id' => env('UNSPLASH_ACCESS_KEY'),
                'per_page' => 3,
                'orientation' => 'landscape'
            ]);

            if ($response->successful()) {
                $fotosApi = $response->json()['results'];
                
                foreach ($fotosApi as $index => $fotoData) {
                    $urlImagen = $fotoData['urls']['regular'];
                    
                    // Descargar el contenido real de la imagen
                    $imagenContenido = Http::get($urlImagen)->body();
                    
                    // Nombre de archivo con índice para no sobrescribir
                    $nombreArchivo = 'seeder_' . Str::slug($destino['titulo']) . '_' . $index . '.jpg';
                    $rutaCompleta = 'fotos_vacaciones/' . $nombreArchivo;

                    // Guardar físicamente en el disco público
                    Storage::disk('public')->put($rutaCompleta, $imagenContenido);

                    // Crear el registro relacionado en la tabla fotos
                    $vacacion->foto()->create(['ruta' => $rutaCompleta]);
                }
                $this->command->info("📸 Fotos descargadas para: " . $destino['titulo']);
            } else {
                // Esto te mostrará el error 403 actual hasta que cambies la Key
                $this->command->error("Fallo API en " . $destino['titulo'] . ": " . $response->status());
            }
        }
    }
}