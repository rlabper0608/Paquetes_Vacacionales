<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VacacionSeeder extends Seeder
{
    public function run(): void
    {
        // Obtenemos los IDs de los tipos para asignarlos correctamente
        $playaId = DB::table('tipo')->where('nombre', 'Playa')->value('id');
        $montanaId = DB::table('tipo')->where('nombre', 'Montaña')->value('id');
        $culturalId = DB::table('tipo')->where('nombre', 'Cultural')->value('id');

        $vacaciones = [
            [
                'titulo' => 'Escapada a las Islas Maldivas',
                'descripcion' => 'Disfruta de aguas cristalinas y villas sobre el mar en un entorno paradisíaco único en el mundo.',
                'precio' => 1250.50,
                'idtipo' => $playaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Senderismo por los Alpes Suizos',
                'descripcion' => 'Rutas increíbles entre montañas nevadas y lagos alpinos. Incluye guía profesional y alojamiento en refugios.',
                'precio' => 890.00,
                'idtipo' => $montanaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Tour Histórico por Roma y el Vaticano',
                'descripcion' => 'Visita guiada al Coliseo, los Museos Vaticanos y la Capilla Sixtina. Incluye vuelos y hotel céntrico.',
                'precio' => 650.75,
                'idtipo' => $culturalId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Relax Total en la Costa del Sol',
                'descripcion' => 'Una semana en hotel 5 estrellas frente al mar con todo incluido. Ideal para desconectar del estrés.',
                'precio' => 540.00,
                'idtipo' => $playaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('vacacion')->insert($vacaciones);
    }
}