<?php

namespace Wave8\Factotum\Base\Policies;

use Wave8\Factotum\Base\Enums\Permission\UserPermission;
use Wave8\Factotum\Base\Models\User;

class UserPolicy
{
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(UserPermission::CREATE_USERS);
    }

    public function read(User $user): bool
    {
        return $user->hasPermissionTo(UserPermission::READ_USERS);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(UserPermission::UPDATE_USERS);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(UserPermission::DELETE_USERS);
    }
}
