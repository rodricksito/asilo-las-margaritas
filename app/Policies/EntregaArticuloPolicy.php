<?php

namespace App\Policies;

use App\Models\EntregaArticulo;
use App\Models\User;

class EntregaArticuloPolicy extends BasePolicy
{
    // Recepcionista y enfermera registran las entregas de artículos
    // personales que trae el familiar (PDF pasos 6-7). El doctor no
    // necesita ver esto.

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['recepcionista', 'enfermera']);
    }

    public function view(User $user, EntregaArticulo $entrega): bool
    {
        return $user->hasAnyRole(['recepcionista', 'enfermera']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['recepcionista', 'enfermera']);
    }

    public function update(User $user, EntregaArticulo $entrega): bool
    {
        return $user->hasAnyRole(['recepcionista', 'enfermera']);
    }

    public function delete(User $user, EntregaArticulo $entrega): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
