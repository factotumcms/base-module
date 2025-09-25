<?php

namespace Wave8\Factotum\Base\Policies;

use Illuminate\Http\Request;
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
        return $user->hasPermissionTo(Permission::READ_USERS);
    }

    public function update(User $user): bool
    {
        $idParam = request()->route('id');

        if ($user->hasPermissionTo(Permission::UPDATE_USERS)) {
            return true;
        }

        return $user->hasPermissionTo(Permission::UPDATE_OWN_USER) && $user->id == $idParam;
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_USERS);
    }
}
