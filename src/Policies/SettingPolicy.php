<?php

namespace Wave8\Factotum\Base\Policies;

use Wave8\Factotum\Base\Enums\Permission\SettingPermission;
use Wave8\Factotum\Base\Models\Setting;
use Wave8\Factotum\Base\Models\User;

class SettingPolicy
{
    public function read(User $user): bool
    {
        return $user->hasPermissionTo(SettingPermission::READ_SETTINGS);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(SettingPermission::UPDATE_SETTINGS);
    }

    public function updateUserSetting(User $user, Setting $setting): bool
    {
        return $setting->is_editable
            && $user->hasPermissionTo(SettingPermission::UPDATE_SETTINGS);
    }
}
