<?php

namespace Wave8\Factotum\Base\Policies;

use Wave8\Factotum\Base\Enum\Permission;
use Wave8\Factotum\Base\Models\User;

class RolePolicy
{
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_ROLE);
    }

    public function read(User $user): bool
    {
        return $user->hasPermissionTo(Permission::READ_ROLE);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permission::UPDATE_ROLE);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_ROLE);
    }
}
