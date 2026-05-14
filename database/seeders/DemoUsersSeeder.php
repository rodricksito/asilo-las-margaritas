<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Enfermera;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Crea 4 usuarios demo (uno por rol) para probar el sistema de permisos.
 *
 * IDEMPOTENTE: Si vuelves a correr este seeder, solo actualiza los
 * usuarios existentes (busca por email). Tu admin original NO se
 * borra ni se modifica si tiene un email distinto.
 *
 * Vincula doctor@asilo.test y enfermera@asilo.test a un Doctor y
 * Enfermera del DemoSeeder, así pueden verse en el panel como
 * miembros del personal real.
 *
 * Uso: php artisan db:seed --class=DemoUsersSeeder
 */
class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $sucursal = Sucursal::first();

        if (! $sucursal) {
            $this->command->error('No hay sucursales en la base. Corre primero DemoSeeder.');
            return;
        }

        $defaultPassword = Hash::make('password');

        // 1. Admin demo (separado de tu admin real para no pisarlo)
        User::updateOrCreate(
            ['email' => 'admin@asilo.test'],
            [
                'name' => 'Admin Demo',
                'password' => $defaultPassword,
                'rol' => 'admin',
                'sucursal_id' => $sucursal->id,
            ]
        );

        // 2. Recepcionista
        User::updateOrCreate(
            ['email' => 'recepcion@asilo.test'],
            [
                'name' => 'Recepcionista Demo',
                'password' => $defaultPassword,
                'rol' => 'recepcionista',
                'sucursal_id' => $sucursal->id,
            ]
        );

        // 3. Doctor — buscamos un Doctor existente sin cuenta vinculada
        //    para enlazarlo. Si no hay, el usuario funciona pero sin
        //    relación a un registro de Doctor.
        $doctorUser = User::updateOrCreate(
            ['email' => 'doctor@asilo.test'],
            [
                'name' => 'Doctor Demo',
                'password' => $defaultPassword,
                'rol' => 'doctor',
                'sucursal_id' => $sucursal->id,
            ]
        );

        $doctorRecord = Doctor::whereNull('usuario_id')->first();
        if ($doctorRecord) {
            $doctorRecord->update(['usuario_id' => $doctorUser->id]);
        }

        // 4. Enfermera — mismo patrón que doctor
        $enfermeraUser = User::updateOrCreate(
            ['email' => 'enfermera@asilo.test'],
            [
                'name' => 'Enfermera Demo',
                'password' => $defaultPassword,
                'rol' => 'enfermera',
                'sucursal_id' => $sucursal->id,
            ]
        );

        $enfermeraRecord = Enfermera::whereNull('usuario_id')->first();
        if ($enfermeraRecord) {
            $enfermeraRecord->update(['usuario_id' => $enfermeraUser->id]);
        }

        $this->command->info('');
        $this->command->info('✓ 4 usuarios demo listos (password: "password"):');
        $this->command->info('  · admin@asilo.test       (Administrador — ve y hace todo)');
        $this->command->info('  · recepcion@asilo.test   (Recepcionista — pacientes, familiares, solicitudes)');
        $this->command->info('  · doctor@asilo.test      (Doctor — emite recetas)');
        $this->command->info('  · enfermera@asilo.test   (Enfermera — solicitudes, entregas)');
        $this->command->info('');
    }
}
