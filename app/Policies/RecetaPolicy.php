<?php

namespace App\Policies;

use App\Models\Receta;
use App\Models\User;

class RecetaPolicy extends BasePolicy
{
    // El doctor emite recetas (PDF paso 1). Recepcionista y enfermera
    // las consultan para procesar solicitudes de medicamentos.

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['recepcionista', 'doctor', 'enfermera']);
    }

    public function view(User $user, Receta $receta): bool
    {
        return $user->hasAnyRole(['recepcionista', 'doctor', 'enfermera']);
    }

    public function create(User $user): bool
    {
        return $user->isDoctor();
    }

    public function update(User $user, Receta $receta): bool
    {
        // Solo el doctor que la emitió puede modificarla.
        return $user->isDoctor() && $receta->doctor->usuario_id === $user->id;
    }

    public function delete(User $user, Receta $receta): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
