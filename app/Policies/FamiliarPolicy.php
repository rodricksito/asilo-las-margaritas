<?php

namespace App\Policies;

use App\Models\Familiar;
use App\Models\User;

class FamiliarPolicy extends BasePolicy
{
    // Recepcionista los registra al ingreso del paciente (PDF paso 1).
    // Los demás roles los consultan para identificación.

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['recepcionista', 'doctor', 'enfermera']);
    }

    public function view(User $user, Familiar $familiar): bool
    {
        return $user->hasAnyRole(['recepcionista', 'doctor', 'enfermera']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['recepcionista', 'enfermera']);
    }

    public function update(User $user, Familiar $familiar): bool
    {
        return $user->isRecepcionista();
    }

    public function delete(User $user, Familiar $familiar): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
