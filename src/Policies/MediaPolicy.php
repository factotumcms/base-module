<?php

namespace Wave8\Factotum\Base\Policies;

use Wave8\Factotum\Base\Enums\Permission;
use Wave8\Factotum\Base\Models\User;

class MediaPolicy
{
    public function read(User $user): bool
    {
        return $user->hasPermissionTo(Permission::READ_MEDIA);
    }

    public function upload(User $user): bool
    {
        return $user->hasPermissionTo(Permission::UPLOAD_MEDIA);
    }
}
