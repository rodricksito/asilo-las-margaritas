<?php

namespace App\Policies;

use App\Models\Enfermera;
use App\Models\User;

class EnfermeraPolicy extends BasePolicy
{
    // Mismo patrón que DoctorPolicy: catálogo gestionado por admin,
    // los demás lo consultan.

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['recepcionista', 'doctor', 'enfermera']);
    }

    public function view(User $user, Enfermera $enfermera): bool
    {
        return $user->hasAnyRole(['recepcionista', 'doctor', 'enfermera']);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Enfermera $enfermera): bool
    {
        return false;
    }

    public function delete(User $user, Enfermera $enfermera): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
