<?php

namespace App\Policies;

use App\Models\Medicamento;
use App\Models\User;

class MedicamentoPolicy extends BasePolicy
{
    // El catálogo de medicamentos lo gestiona admin (validación de
    // caducidad, stock, etc.). Los demás roles consultan inventario.

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['recepcionista', 'doctor', 'enfermera']);
    }

    public function view(User $user, Medicamento $medicamento): bool
    {
        return $user->hasAnyRole(['recepcionista', 'doctor', 'enfermera']);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Medicamento $medicamento): bool
    {
        return false;
    }

    public function delete(User $user, Medicamento $medicamento): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
