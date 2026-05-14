<?php

namespace App\Policies;

use App\Models\Sucursal;
use App\Models\User;

class SucursalPolicy extends BasePolicy
{
    // Admin pasa via before(). Nadie más toca sucursales.

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Sucursal $sucursal): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Sucursal $sucursal): bool
    {
        return false;
    }

    public function delete(User $user, Sucursal $sucursal): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
