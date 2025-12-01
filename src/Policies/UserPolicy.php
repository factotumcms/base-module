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

    public function filter(User $user): bool
    {
        return $user->hasPermissionTo(UserPermission::READ_USERS);
    }

    public function read(User $authUser, User $user): bool
    {
        return $authUser->hasPermissionTo(UserPermission::READ_USERS);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(UserPermission::UPDATE_USERS);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(UserPermission::DELETE_USERS);
    }

    public function changePassword(User $authUser, User $user): bool
    {
        return $authUser->hasPermissionTo(UserPermission::UPDATE_USERS);
    }
}
