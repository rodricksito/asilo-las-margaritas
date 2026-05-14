<?php

namespace App\Policies;

use App\Models\Traspaso;
use App\Models\User;

class TraspasoPolicy extends BasePolicy
{
    // Movimientos de inventario entre sucursales — operación administrativa.
    // Solo admin (via before).

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Traspaso $traspaso): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Traspaso $traspaso): bool
    {
        return false;
    }

    public function delete(User $user, Traspaso $traspaso): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
