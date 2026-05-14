<?php

namespace App\Policies;

use App\Models\Doctor;
use App\Models\User;

class DoctorPolicy extends BasePolicy
{
    // Catálogo de personal médico — admin lo gestiona, los demás roles
    // solo necesitan verlo (para asignar pacientes, hacer recetas, etc.)

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['recepcionista', 'doctor', 'enfermera']);
    }

    public function view(User $user, Doctor $doctor): bool
    {
        return $user->hasAnyRole(['recepcionista', 'doctor', 'enfermera']);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Doctor $doctor): bool
    {
        return false;
    }

    public function delete(User $user, Doctor $doctor): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
