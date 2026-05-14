<?php

namespace App\Policies;

use App\Models\Paciente;
use App\Models\User;

class PacientePolicy extends BasePolicy
{
    // Recepcionista da de alta pacientes y los gestiona (regla del PDF).
    // Doctor y enfermera los consultan para atención médica.

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['recepcionista', 'doctor', 'enfermera']);
    }

    public function view(User $user, Paciente $paciente): bool
    {
        return $user->hasAnyRole(['recepcionista', 'doctor', 'enfermera']);
    }

    public function create(User $user): bool
    {
        return $user->isRecepcionista();
    }

    public function update(User $user, Paciente $paciente): bool
    {
        return $user->isRecepcionista();
    }

    public function delete(User $user, Paciente $paciente): bool
    {
        return false; // solo admin via before()
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
