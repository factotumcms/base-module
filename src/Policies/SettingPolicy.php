<?php

namespace Wave8\Factotum\Base\Policies;

use Wave8\Factotum\Base\Enums\Permission;
use Wave8\Factotum\Base\Models\User;

class SettingPolicy
{
    public function read(User $user): bool
    {
        return $user->hasPermissionTo(Permission::UPDATE_SETTINGS);
    }

    public function delete(): bool
    {
        return false;
    }
}
