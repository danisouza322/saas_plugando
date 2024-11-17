<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Role;

class UserObserver
{
    public function created(User $user)
    {
        // Atribui o role 'user' por padrão a novos usuários
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole);
        }
    }
}
