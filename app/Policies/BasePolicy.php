<?php

namespace App\Policies;

use App\Models\User;

/**
 * Policy base que provee el comportamiento "admin puede todo" mediante
 * el hook before(). Las policies hijas solo declaran reglas para los
 * roles no-admin.
 *
 * Cómo funciona: Laravel ejecuta before() ANTES de cada método de la
 * policy. Si retorna true, la operación se permite sin evaluar el método
 * específico. Si retorna null, sigue al método normal.
 */
abstract class BasePolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }
}
