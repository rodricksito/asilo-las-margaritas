<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Orden importante:
     *   1. DemoSeeder       — crea sucursales, doctores, pacientes, medicamentos,
     *                         recetas, solicitudes, traspasos, etc.
     *   2. DemoUsersSeeder  — crea los 4 usuarios demo (uno por rol). Corre
     *                         DESPUES porque necesita que existan las sucursales.
     */
    public function run(): void
    {
        $this->call([
            DemoSeeder::class,
            DemoUsersSeeder::class,
        ]);
    }
}
