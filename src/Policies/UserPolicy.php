<?php

namespace Wave8\Factotum\Base\Policies;

use Wave8\Factotum\Base\Enums\Permission;
use Wave8\Factotum\Base\Models\User;

class UserPolicy
{
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_USER);
    }

    public function read(User $user): bool
    {
        return $user->hasPermissionTo(Permission::READ_USER);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permission::UPDATE_USER);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_USER);
    }
}
