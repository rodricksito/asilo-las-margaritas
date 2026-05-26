<?php

namespace Database\Seeders;

use App\Models\ArticuloPersonal;
use App\Models\Doctor;
use App\Models\Enfermera;
use App\Models\EntregaArticulo;
use App\Models\Familiar;
use App\Models\Medicamento;
use App\Models\Paciente;
use App\Models\Receta;
use App\Models\Solicitud;
use App\Models\Sucursal;

use App\Models\Traspaso;
use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Generando datos demo del Asilo Las Margaritas...');
        $this->command->newLine();

        $this->truncateAll();

        [$torreon, $gomez] = $this->seedSucursales();
        $doctores = $this->seedDoctores($torreon, $gomez);
        $enfermeras = $this->seedEnfermeras($torreon, $gomez);
        $articulos = $this->seedArticulos();
        $medicamentos = $this->seedMedicamentos($torreon, $gomez);
        $familiares = $this->seedFamiliares();
        $pacientes = $this->seedPacientes($torreon, $gomez, $doctores, $familiares);
        $recetas = $this->seedRecetas($pacientes, $doctores, $medicamentos);
        $this->seedSolicitudes($recetas, $enfermeras, $articulos);
        $this->seedTraspasos($medicamentos, $torreon, $gomez);

        $this->printSummary();
    }

    // ====================================================================
    // Truncate (preservando users)
    // ====================================================================
    private function truncateAll(): void
    {
        Schema::disableForeignKeyConstraints();

        EntregaArticulo::truncate();
        DB::table('medicamento_solicitud')->truncate();
        Solicitud::truncate();
        DB::table('medicamento_receta')->truncate();
        Receta::truncate();
        DB::table('familiar_paciente')->truncate();
        Paciente::truncate();
        Medicamento::truncate();
        Familiar::truncate();
        ArticuloPersonal::truncate();
        Enfermera::truncate();
        Doctor::truncate();
        DB::table('traspasos')->truncate();
        Sucursal::truncate();

        Schema::enableForeignKeyConstraints();

        $this->command->info('   🧹 Tablas limpiadas (usuarios preservados)');
    }

    // ====================================================================
    // Sucursales
    // ====================================================================
    private function seedSucursales(): array
    {
        $torreon = Sucursal::create([
            'nombre' => 'Asilo Las Margaritas - Torreón Centro',
            'direccion' => 'Av. Juárez 1245, Col. Centro, Torreón, Coahuila',
            'telefono' => '8717181920',
            'activa' => true,
        ]);

        $gomez = Sucursal::create([
            'nombre' => 'Asilo Las Margaritas - Gómez Palacio',
            'direccion' => 'Blvd. Miguel Alemán 567, Gómez Palacio, Durango',
            'telefono' => '8716543210',
            'activa' => true,
        ]);

        $this->command->info('   ✓ 2 sucursales');

        return [$torreon, $gomez];
    }

    // ====================================================================
    // Doctores
    // ====================================================================
    private function seedDoctores(Sucursal $torreon, Sucursal $gomez): array
    {
        $data = [
            ['Dr. Eduardo Mendoza Aguilar', 'Geriatría', '1234567', '8711100001', $torreon],
            ['Dra. Rosa María Castillo Vega', 'Medicina interna', '2345678', '8711100002', $torreon],
            ['Dr. José Antonio Ramírez Solís', 'Cardiología', '3456789', '8711100003', $torreon],
            ['Dra. Laura Patricia Treviño Mata', 'Geriatría', '4567890', '8711100004', $gomez],
        ];

        $doctores = [];
        foreach ($data as [$nombre, $especialidad, $cedula, $telefono, $sucursal]) {
            $doctores[] = Doctor::create([
                'sucursal_id' => $sucursal->id,
                'nombre' => $nombre,
                'cedula' => $cedula,
                'especialidad' => $especialidad,
                'telefono' => $telefono,
                'activo' => true,
            ]);
        }

        $this->command->info('   ✓ ' . count($doctores) . ' doctores');

        return $doctores;
    }

    // ====================================================================
    // Enfermeras
    // ====================================================================
    private function seedEnfermeras(Sucursal $torreon, Sucursal $gomez): array
    {
        $data = [
            ['Patricia Hernández Ortiz', 'matutino', '8712200001', $torreon],
            ['Verónica López Salazar', 'vespertino', '8712200002', $torreon],
            ['Karla Sánchez Domínguez', 'nocturno', '8712200003', $torreon],
            ['Adriana Reyes Maldonado', 'matutino', '8712200004', $gomez],
            ['Fernanda Castro Ramírez', 'vespertino', '8712200005', $gomez],
        ];

        $enfermeras = [];
        foreach ($data as [$nombre, $turno, $telefono, $sucursal]) {
            $enfermeras[] = Enfermera::create([
                'sucursal_id' => $sucursal->id,
                'nombre' => $nombre,
                'turno' => $turno,
                'telefono' => $telefono,
                'activa' => true,
            ]);
        }

        $this->command->info('   ✓ ' . count($enfermeras) . ' enfermeras');

        return $enfermeras;
    }

    // ====================================================================
    // Artículos personales
    // ====================================================================
    private function seedArticulos(): array
    {
        $data = [
            ['Pasta de dientes', 'Para higiene dental diaria'],
            ['Cepillo de dientes', 'Cerdas suaves para encías sensibles'],
            ['Jabón de tocador', 'Hipoalergénico'],
            ['Shampoo neutro', 'Para cabello y cuero cabelludo'],
            ['Crema corporal hidratante', 'Para piel reseca'],
            ['Pañales para adulto', 'Talla M, alta absorción'],
            ['Toallas húmedas', 'Para limpieza diaria'],
            ['Pañuelos desechables', 'Caja de 100 piezas'],
            ['Desodorante en barra', 'Sin alcohol'],
            ['Talco corporal', 'Para prevenir rozaduras'],
            ['Rastrillos desechables', 'Paquete de 10'],
            ['Limpiador de dentadura', 'Tabletas efervescentes'],
        ];

        $articulos = [];
        foreach ($data as [$nombre, $descripcion]) {
            $articulos[] = ArticuloPersonal::create([
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'activo' => true,
            ]);
        }

        $this->command->info('   ✓ ' . count($articulos) . ' artículos personales');

        return $articulos;
    }

    // ====================================================================
    // Medicamentos (con caducidades estratégicas para que widgets luzcan)
    // ====================================================================
    private function seedMedicamentos(Sucursal $torreon, Sucursal $gomez): array
    {
        $now = now();

        // [nombre, presentacion, meses_hasta_caducidad, dias_extra, stock]
        // 4 medicamentos próximos a caducar (≤ 3 meses) para que el KPI alerte
        $data = [
            // Diabetes
            ['Metformina', 'Tabletas 500mg', 6, 0, 480],
            ['Glibenclamida', 'Tabletas 5mg', 4, 0, 220],
            ['Insulina NPH', 'Frasco 10ml inyectable', 8, 0, 35],
            // Hipertensión
            ['Losartán', 'Tabletas 50mg', 5, 0, 540],
            ['Enalapril', 'Tabletas 10mg', 7, 0, 360],
            ['Amlodipino', 'Tabletas 5mg', 9, 0, 280],
            // Colesterol
            ['Atorvastatina', 'Tabletas 20mg', 2, 0, 180],          // ⚠️ por caducar
            ['Simvastatina', 'Tabletas 40mg', 6, 0, 150],
            // Tiroides
            ['Levotiroxina', 'Tabletas 100mcg', 4, 0, 200],
            // Reflujo
            ['Omeprazol', 'Cápsulas 20mg', 1, 15, 240],             // ⚠️ por caducar
            ['Pantoprazol', 'Tabletas 40mg', 6, 0, 110],
            // Anticoagulantes
            ['Warfarina', 'Tabletas 5mg', 5, 0, 140],
            // Dolor
            ['Paracetamol', 'Tabletas 500mg', 8, 0, 600],
            ['Tramadol', 'Cápsulas 50mg', 4, 0, 80],
            // Memoria / demencia
            ['Donepezilo', 'Tabletas 10mg', 7, 0, 100],
            ['Memantina', 'Tabletas 10mg', 6, 0, 90],
            // Próstata
            ['Tamsulosina', 'Cápsulas 0.4mg', 5, 0, 120],
            ['Finasterida', 'Tabletas 5mg', 9, 0, 90],
            // Calcio / huesos
            ['Carbonato de calcio + D3', 'Tabletas masticables 600mg', 2, 20, 280],  // ⚠️ por caducar
            ['Alendronato', 'Tabletas 70mg', 7, 0, 40],
            // Salud mental
            ['Sertralina', 'Tabletas 50mg', 6, 0, 130],
            // Sueño
            ['Zolpidem', 'Tabletas 10mg', 2, 0, 75],                // ⚠️ por caducar
        ];

        $medicamentos = [];
        foreach ($data as $i => [$nombre, $presentacion, $meses, $diasExtra, $stock]) {
            $sucursal = $i % 2 === 0 ? $torreon : $gomez;
            $caducidad = $now->copy()->addMonths($meses)->addDays($diasExtra);

            $medicamentos[] = Medicamento::create([
                'sucursal_id' => $sucursal->id,
                'nombre' => $nombre,
                'presentacion' => $presentacion,
                'fecha_caducidad' => $caducidad,
                'stock' => $stock,
                'activo' => true,
            ]);
        }

        $this->command->info('   ✓ ' . count($medicamentos) . ' medicamentos (4 por caducar pronto)');

        return $medicamentos;
    }

    // ====================================================================
    // Familiares (hijos, hijas, esposos, hermanos, nietos)
    // ====================================================================
    private function seedFamiliares(): array
    {
        $data = [
            ['Carlos Eduardo González Pérez', 'Hijo', '8711234567', 'carlos.gonzalez@gmail.com'],
            ['Ana María Rivera Soto', 'Hija', '8712345678', 'ana.rivera@hotmail.com'],
            ['Diego Armando Ramírez González', 'Hijo', '8713456789', 'd.ramirez@yahoo.com'],
            ['Patricia López Hernández', 'Hija', '8714567890', 'paty.lopez@gmail.com'],
            ['Sandra Verónica Hernández Flores', 'Hija', '8715678901', 'sandra.hf@outlook.com'],
            ['Roberto Carlos Cruz Martínez', 'Hijo', '8716789012', 'roberto.cruz@gmail.com'],
            ['Verónica Cristina Vargas Pérez', 'Hija', '8717890123', null],
            ['Sergio Alejandro Sánchez Vargas', 'Hijo', '8718901234', 'sergio.sv@gmail.com'],
            ['Mónica Adriana Flores López', 'Hija', '8719012345', 'monica.flores@gmail.com'],
            ['José Luis Méndez Cárdenas', 'Hijo', '8710123456', null],
            ['Karla Daniela Gómez Reyes', 'Hija', '8711122334', 'karla.gomez@hotmail.com'],
            ['Luis Fernando Reyes Ramos', 'Hijo', '8712233445', 'luis.fernando@gmail.com'],
            ['Diana Elizabeth Torres Núñez', 'Hija', '8713344556', 'diana.torres@gmail.com'],
            ['Andrés Felipe Morales Romo', 'Hijo', '8714455667', null],
            ['Lorena Beatriz Castillo Aguirre', 'Hija', '8715566778', 'lorena.castillo@gmail.com'],
            ['Miguel Ángel Pérez Ortega', 'Hijo', '8716677889', 'miguel.po@yahoo.com'],
            ['Brenda Paola Ortega Reyes', 'Hija', '8717788990', 'brenda.ortega@gmail.com'],
            ['Jorge Iván Jiménez Salazar', 'Hijo', '8718899001', 'jorge.jimenez@hotmail.com'],
            ['Adriana Marisol García Cervantes', 'Hija', '8719900112', 'adriana.gc@gmail.com'],
            ['Daniel Eduardo Martínez Fonseca', 'Hijo', '8710011223', null],
            ['Fernando Sánchez Aguirre', 'Hijo', '8711122335', 'fernando.sa@gmail.com'],
            ['Karina Elena Ruiz Mendoza', 'Hija', '8712233446', 'karina.ruiz@gmail.com'],
            ['Javier Alfonso Castro Vázquez', 'Hijo', '8713344557', 'javier.castro@yahoo.com'],
            ['Rocío del Carmen Vázquez Ramírez', 'Hija', '8714455668', 'rocio.vazquez@gmail.com'],
            ['Hugo César Ramos Delgado', 'Sobrino', '8715566779', null],
        ];

        $familiares = [];
        foreach ($data as [$nombre, $parentesco, $telefono, $email]) {
            $familiares[] = Familiar::create([
                'nombre' => $nombre,
                'parentesco' => $parentesco,
                'telefono' => $telefono,
                'email' => $email,
                'direccion' => 'Torreón, Coahuila',
                'activo' => true,
            ]);
        }

        $this->command->info('   ✓ ' . count($familiares) . ' familiares');

        return $familiares;
    }

    // ====================================================================
    // Pacientes (con vínculos a familiares)
    // ====================================================================
    private function seedPacientes(Sucursal $torreon, Sucursal $gomez, array $doctores, array $familiares): \Illuminate\Support\Collection
    {
        // [nombre, año_nacimiento, sexo (M/F), familiares_indices_principal_y_secundarios]
        $data = [
            ['María Guadalupe González Rivera', 1948, 'F', [0, 1]],
            ['José Antonio Ramírez López', 1942, 'M', [2]],
            ['Rosa Margarita Hernández Cruz', 1955, 'F', [3, 4]],
            ['Pedro Manuel Vargas Sánchez', 1939, 'M', [6, 7]],
            ['Juana María Flores Méndez', 1950, 'F', [8, 9]],
            ['Antonio Salvador Gómez Reyes', 1945, 'M', [10]],
            ['Esperanza Concepción Torres Núñez', 1936, 'F', [11, 12]],
            ['Francisco Javier Morales Castillo', 1947, 'M', [13]],
            ['Carmen Dolores Pérez Ortega', 1944, 'F', [15, 16]],
            ['Roberto Alejandro Jiménez García', 1952, 'M', [17]],
            ['Soledad Petra Martínez Fonseca', 1933, 'F', [19]],
            ['Manuel Jesús Sánchez Aguirre', 1949, 'M', [20]],
            ['Teresa Josefina Ruiz Mendoza', 1941, 'F', [21]],
            ['Salvador Eduardo Castro Vázquez', 1937, 'M', [22, 23]],
            ['Beatriz Antonia Ramos Delgado', 1953, 'F', [24]],
            ['Ramón Alfonso Díaz Espinoza', 1946, 'M', [5, 18]],   // familiares compartidos con otros
            ['Lucía Catalina Reyes Maldonado', 1951, 'F', [14]],
            ['Enrique Rafael López Trejo', 1940, 'M', [3]],         // 3 también es familiar de Rosa Margarita
        ];

        $pacientes = collect();
        foreach ($data as $i => [$nombre, $anioNacimiento, $sexo, $familiaresIndices]) {
            // Distribuir entre sucursales: ~75% Torreón, ~25% Gómez
            $sucursal = $i % 4 === 3 ? $gomez : $torreon;

            // Doctor consistente con sucursal cuando es posible
            $doctoresEnSucursal = collect($doctores)->where('sucursal_id', $sucursal->id)->values();
            $doctor = $doctoresEnSucursal->isNotEmpty()
                ? $doctoresEnSucursal->random()
                : $doctores[array_rand($doctores)];

            // Fechas
            $fechaNacimiento = Carbon::create(
                $anioNacimiento,
                random_int(1, 12),
                random_int(1, 28)
            );
            $fechaIngreso = now()->subDays(random_int(180, 1825));  // 6 meses a 5 años atrás

            $observaciones = match ($sexo) {
                'F' => 'Paciente femenino. Requiere asistencia con higiene básica.',
                'M' => 'Paciente masculino. Movilidad reducida, usa andadera.',
            };

            $paciente = Paciente::create([
                'sucursal_id' => $sucursal->id,
                'doctor_id' => $doctor->id,
                'nombre' => $nombre,
                'fecha_nacimiento' => $fechaNacimiento,
                'fecha_ingreso' => $fechaIngreso,
                'estado' => 'activo',
                'observaciones' => $observaciones,
            ]);

            // Vincular familiares (el primero como principal)
            foreach ($familiaresIndices as $j => $familiarIdx) {
                $paciente->familiares()->attach($familiares[$familiarIdx]->id, [
                    'es_principal' => $j === 0,
                ]);
            }

            $pacientes->push($paciente);
        }

        $this->command->info('   ✓ ' . $pacientes->count() . ' pacientes (con familiares vinculados)');

        return $pacientes;
    }

    // ====================================================================
    // Recetas (cada paciente tiene 1-3 recetas con 1-4 medicamentos)
    // ====================================================================
    private function seedRecetas(\Illuminate\Support\Collection $pacientes, array $doctores, array $medicamentos): \Illuminate\Support\Collection
    {
        $recetas = collect();

        // Combinaciones realistas de medicamentos por condición
        // (índices del array $medicamentos: 0..21)
        $combosClinicos = [
            ['nombre' => 'Diabetes tipo 2', 'meds' => [0, 3, 6]],            // Metformina + Losartán + Atorvastatina
            ['nombre' => 'Hipertensión', 'meds' => [3, 5]],                  // Losartán + Amlodipino
            ['nombre' => 'Diabetes + Cardio', 'meds' => [0, 3, 11]],         // Metformina + Losartán + Warfarina
            ['nombre' => 'Demencia inicial', 'meds' => [14, 15, 20]],        // Donepezilo + Memantina + Sertralina
            ['nombre' => 'Hipotiroidismo', 'meds' => [8, 18]],               // Levotiroxina + Calcio
            ['nombre' => 'Reflujo + dolor', 'meds' => [9, 12]],              // Omeprazol + Paracetamol
            ['nombre' => 'Próstata', 'meds' => [16, 17]],                    // Tamsulosina + Finasterida
            ['nombre' => 'Insomnio + ansiedad', 'meds' => [21, 20]],         // Zolpidem + Sertralina
            ['nombre' => 'Osteoporosis', 'meds' => [18, 19]],                // Calcio + Alendronato
            ['nombre' => 'Diabetes avanzada', 'meds' => [2, 0]],             // Insulina + Metformina
            ['nombre' => 'Hipertensión + colesterol', 'meds' => [4, 7]],     // Enalapril + Simvastatina
        ];

        foreach ($pacientes as $paciente) {
            // 1-3 recetas por paciente (la mayoría 2)
            $numRecetas = random_int(1, 3);

            for ($i = 0; $i < $numRecetas; $i++) {
                $combo = $combosClinicos[array_rand($combosClinicos)];
                $doctorRandom = $doctores[array_rand($doctores)];

                // Fecha de receta entre 1 y 90 días atrás
                $fecha = now()->subDays(random_int(1, 90));
                $vigencia = $fecha->copy()->addDays(random_int(120, 180));

                $receta = Receta::create([
                    'paciente_id' => $paciente->id,
                    'doctor_id' => $doctorRandom->id,
                    'fecha' => $fecha,
                    'vigencia' => $vigencia,
                    'observaciones' => 'Diagnóstico: ' . $combo['nombre'] . '. Revisar en próxima consulta.',
                ]);

                // Adjuntar medicamentos al pivote con dosis/frecuencia/cantidad reales
                foreach ($combo['meds'] as $medIdx) {
                    if (! isset($medicamentos[$medIdx])) {
                        continue;
                    }
                    $med = $medicamentos[$medIdx];
                    [$dosis, $frecuencia, $duracion, $cantidad] = $this->dosisRealParaMedicamento($med->nombre);

                    $receta->medicamentos()->attach($med->id, [
                        'dosis' => $dosis,
                        'frecuencia' => $frecuencia,
                        'cantidad' => $cantidad,
                        'duracion_dias' => $duracion,
                    ]);
                }

                $recetas->push($receta);
            }
        }

        $this->command->info('   ✓ ' . $recetas->count() . ' recetas (con medicamentos asociados)');

        return $recetas;
    }

    /**
     * Devuelve [dosis, frecuencia, duracion_dias, cantidad_total] realista por medicamento.
     */
    private function dosisRealParaMedicamento(string $nombre): array
    {
        return match ($nombre) {
            'Metformina'                => ['1 tableta', 'cada 12 horas', 30, 60],
            'Glibenclamida'             => ['1 tableta', 'antes del desayuno', 30, 30],
            'Insulina NPH'              => ['10 unidades', 'antes de dormir', 30, 1],
            'Losartán'                  => ['1 tableta', 'cada 24 horas', 30, 30],
            'Enalapril'                 => ['1 tableta', 'cada 12 horas', 30, 60],
            'Amlodipino'                => ['1 tableta', 'cada 24 horas', 30, 30],
            'Atorvastatina'             => ['1 tableta', 'antes de dormir', 30, 30],
            'Simvastatina'              => ['1 tableta', 'antes de dormir', 30, 30],
            'Levotiroxina'              => ['1 tableta', 'en ayunas', 30, 30],
            'Omeprazol'                 => ['1 cápsula', 'antes del desayuno', 30, 30],
            'Pantoprazol'               => ['1 tableta', 'antes del desayuno', 30, 30],
            'Warfarina'                 => ['1 tableta', 'cada 24 horas', 30, 30],
            'Paracetamol'               => ['1 tableta', 'cada 8 horas', 15, 45],
            'Tramadol'                  => ['1 cápsula', 'cada 12 horas', 15, 30],
            'Donepezilo'                => ['1 tableta', 'antes de dormir', 30, 30],
            'Memantina'                 => ['1 tableta', 'cada 12 horas', 30, 60],
            'Tamsulosina'               => ['1 cápsula', 'cada 24 horas', 30, 30],
            'Finasterida'               => ['1 tableta', 'cada 24 horas', 30, 30],
            'Carbonato de calcio + D3'  => ['1 tableta masticable', 'cada 12 horas', 30, 60],
            'Alendronato'               => ['1 tableta', 'una vez por semana', 28, 4],
            'Sertralina'                => ['1 tableta', 'por la mañana', 30, 30],
            'Zolpidem'                  => ['1 tableta', 'antes de dormir', 30, 30],
            default                     => ['1 unidad', 'cada 12 horas', 30, 30],
        };
    }

    // ====================================================================
    // Solicitudes (con distribución estratégica para que el dashboard luzca)
    // ====================================================================
    private function seedSolicitudes(\Illuminate\Support\Collection $recetas, array $enfermeras, array $articulos): void
    {
        // Plan de distribución:
        //   50 completas: 25 últimos 30 días, 15 hace 30-60 días, 10 hace 60-90 días
        //    8 incompletas vigentes (fecha 0-2 días) → fecha_limite en futuro
        //   12 vencidas (fecha 5-90 días) → fecha_limite ya pasó
        //   = 70 solicitudes total
        $plan = [
            ['n' => 25, 'days_min' => 0,  'days_max' => 30, 'estado' => 'completa'],
            ['n' => 15, 'days_min' => 30, 'days_max' => 60, 'estado' => 'completa'],
            ['n' => 10, 'days_min' => 60, 'days_max' => 90, 'estado' => 'completa'],
            ['n' => 8,  'days_min' => 0,  'days_max' => 2,  'estado' => 'incompleta'],
            ['n' => 12, 'days_min' => 5,  'days_max' => 90, 'estado' => 'vencida'],
        ];

        $totalCreadas = 0;
        $totalConArticulos = 0;

        foreach ($plan as $grupo) {
            for ($i = 0; $i < $grupo['n']; $i++) {
                $receta = $recetas->random();
                $paciente = $receta->paciente;
                $familiares = $paciente->familiares;
                if ($familiares->isEmpty()) {
                    continue;
                }

                $daysAgo = random_int($grupo['days_min'], $grupo['days_max']);
                $fecha = now()->subDays($daysAgo)->setTime(random_int(8, 17), random_int(0, 59));

                $estado = $grupo['estado'];
                $fechaLimite = $estado === 'completa'
                    ? null
                    : $fecha->copy()->addDays(3)->startOfDay();

                $solicitud = Solicitud::create([
                    'paciente_id' => $paciente->id,
                    'familiar_id' => $familiares->random()->id,
                    'enfermera_id' => $enfermeras[array_rand($enfermeras)]->id,
                    'receta_id' => $receta->id,
                    'fecha' => $fecha,
                    'estado' => $estado,
                    'fecha_limite' => $fechaLimite,
                    'observaciones' => $this->observacionPorEstado($estado),
                ]);

                // Pivote medicamento_solicitud
                foreach ($receta->medicamentos as $med) {
                    $solicitada = (int) $med->pivot->cantidad;
                    $recibida = match ($estado) {
                        'completa' => $solicitada,
                        'incompleta' => (int) round($solicitada * (random_int(20, 75) / 100)),
                        'vencida' => (int) round($solicitada * (random_int(0, 50) / 100)),
                        default => 0,
                    };

                    $solicitud->medicamentos()->attach($med->id, [
                        'cantidad_solicitada' => $solicitada,
                        'cantidad_recibida' => $recibida,
                    ]);
                }

                // ~50% de las solicitudes tiene artículos personales
                if (random_int(1, 100) <= 55) {
                    $numArt = random_int(1, 3);
                    $articulosSelected = collect($articulos)->random($numArt);
                    foreach ($articulosSelected as $art) {
                        EntregaArticulo::create([
                            'solicitud_id' => $solicitud->id,
                            'articulo_id' => $art->id,
                            'paciente_id' => $paciente->id,
                            'cantidad' => random_int(1, 4),
                            'fecha' => $fecha->toDateString(),
                            'observaciones' => null,
                        ]);
                    }
                    $totalConArticulos++;
                }

                $totalCreadas++;
            }
        }

        $this->command->info('   ✓ ' . $totalCreadas . " solicitudes ({$totalConArticulos} con artículos)");
    }

    private function seedTraspasos(array $medicamentos, Sucursal $torreon, Sucursal $gomez): void
    {
        $usuario = User::first();
        $usuarioId = $usuario?->id;
        
        $data = [
            [0,  100, $torreon, $gomez,   45, 'completado', 'Reabastecimiento mensual de Gómez Palacio.'],
            [3,   80, $torreon, $gomez,   38, 'completado', 'Stock bajo en sucursal destino.'],
            [6,   50, $gomez,   $torreon, 30, 'completado', 'Redistribución por demanda en Torreón Centro.'],
            [12, 150, $torreon, $gomez,   22, 'completado', 'Paracetamol para inventario de invierno.'],
            [9,   60, $gomez,   $torreon, 15, 'completado', 'Omeprazol próximo a caducar, se concentra en Torreón.'],
            [14,  25, $torreon, $gomez,   10, 'pendiente',  'En tránsito — esperando confirmación de recepción.'],
            [20,  40, $gomez,   $torreon, 7,  'completado', 'Sertralina solicitada por área de salud mental.'],
            [3,   30, $torreon, $gomez,   3,  'pendiente',  'Traspaso urgente por agotamiento de stock.'],
        ];

        $total = 0;
        foreach ($data as [$medIdx, $cantidad, $origen, $destino, $diasAtras, $estado, $obs]) {
            if (! isset($medicamentos[$medIdx])) {
                continue;
            }

            Traspaso::create([
                'medicamento_id' => $medicamentos[$medIdx]->id,
                'sucursal_origen_id' => $origen->id,
                'sucursal_destino_id' => $destino->id,
                'usuario_id' => $usuarioId,
                'cantidad' => $cantidad,
                'fecha' => now()->subDays($diasAtras),
                'estado' => $estado,
                'observaciones' => $obs,
            ]);

            $total++;
        }

        $this->command->info('   ✓ ' . $total . ' traspasos entre sucursales');
    }

    private function observacionPorEstado(string $estado): ?string
    {
        $opciones = match ($estado) {
            'completa' => [
                null,
                'Entrega completa. Familiar atento.',
                'Recibido en buenas condiciones.',
                null,
                null,
            ],
            'incompleta' => [
                'Familiar regresará en 2 días con el resto.',
                'Faltó pasar a la farmacia, se compromete a traer mañana.',
                'Pendiente de completar — familiar avisado.',
            ],
            'vencida' => [
                'Familiar no se presentó. Contactar.',
                'Plazo vencido sin completar entrega.',
                'Sin respuesta del familiar tras 3 intentos.',
            ],
            default => [null],
        };

        return $opciones[array_rand($opciones)];
    }

    // ====================================================================
    // Resumen final
    // ====================================================================
    private function printSummary(): void
    {
        $this->command->newLine();
        $this->command->info('🎉 Datos demo generados exitosamente:');
        $this->command->table(
            ['Entidad', 'Total'],
            [
                ['Sucursales', Sucursal::count()],
                ['Doctores', Doctor::count()],
                ['Enfermeras', Enfermera::count()],
                ['Pacientes', Paciente::count()],
                ['Familiares', Familiar::count()],
                ['Medicamentos', Medicamento::count()],
                ['Artículos personales', ArticuloPersonal::count()],
                ['Recetas', Receta::count()],
                ['Solicitudes (todas)', Solicitud::count()],
                ['  └─ Completas', Solicitud::where('estado', 'completa')->count()],
                ['  └─ Incompletas', Solicitud::where('estado', 'incompleta')->count()],
                ['  └─ Vencidas', Solicitud::where('estado', 'vencida')->count()],
                ['Entregas de artículos', EntregaArticulo::count()],
                ['Traspasos', Traspaso::count()],
            ]
        );
    }
}
