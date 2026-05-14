<?php

namespace App\Policies;

use App\Models\Solicitud;
use App\Models\User;

class SolicitudPolicy extends BasePolicy
{
    // Es el corazón operativo. La recepcionista o enfermera registra
    // cuando el familiar trae los medicamentos (PDF pasos 4-8). El doctor
    // las consulta para saber si su receta está siendo cumplida.

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['recepcionista', 'doctor', 'enfermera']);
    }

    public function view(User $user, Solicitud $solicitud): bool
    {
        return $user->hasAnyRole(['recepcionista', 'doctor', 'enfermera']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['recepcionista', 'enfermera']);
    }

    public function update(User $user, Solicitud $solicitud): bool
    {
        return $user->hasAnyRole(['recepcionista', 'enfermera']);
    }

    public function delete(User $user, Solicitud $solicitud): bool
    {
        return false; // solo admin via before()
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
