<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoSeeder extends Seeder {
    public function run(): void {
        $tipos = [
            ['nombre' => 'Playa'],
            ['nombre' => 'Montaña'],
            ['nombre' => 'Cultural'],
            ['nombre' => 'Aventura'],
            ['nombre' => 'Crucero'],
            ['nombre' => 'Ciudad'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo')->insert([
                'nombre' => $tipo['nombre'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}