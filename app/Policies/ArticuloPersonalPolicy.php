<?php

namespace App\Policies;

use App\Models\ArticuloPersonal;
use App\Models\User;

class ArticuloPersonalPolicy extends BasePolicy
{
    // Catálogo administrativo. Recepcionista y enfermera consultan
    // qué artículos puede recibir el paciente. Doctor no lo necesita.

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['recepcionista', 'enfermera']);
    }

    public function view(User $user, ArticuloPersonal $articulo): bool
    {
        return $user->hasAnyRole(['recepcionista', 'enfermera']);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ArticuloPersonal $articulo): bool
    {
        return false;
    }

    public function delete(User $user, ArticuloPersonal $articulo): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
