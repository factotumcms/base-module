<?php

namespace Wave8\Factotum\Base\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Wave8\Factotum\Base\Contracts\Api\RoleServiceInterface;
use Wave8\Factotum\Base\Enums\Permission\RolePermission;
use Wave8\Factotum\Base\Models\User;

class RolePolicy
{
    public function __construct(private RoleServiceInterface $roleService) {}

    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(RolePermission::CREATE_ROLE);
    }

    public function read(User $user): bool
    {
        return $user->hasPermissionTo(RolePermission::READ_ROLE);
    }

    public function update(User $user): bool
    {
        $requestRoleId = (int) request()->route('id');

        // Prevent updates to default roles
        if ($this->roleService->isDefaultRole(roleId: $requestRoleId)) {
            return false;
        }

        return $user->hasPermissionTo(RolePermission::UPDATE_ROLE);
    }

    public function delete(User $user): bool
    {
        $requestRoleId = (int) request()->route('id');

        if ($this->roleService->isDefaultRole(roleId: $requestRoleId)) {
            return false;
        }

        return $user->hasPermissionTo(RolePermission::DELETE_ROLE);
    }
}
