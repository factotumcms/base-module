<?php

namespace Wave8\Factotum\Base\Policies;

use Wave8\Factotum\Base\Enums\Permission\MediaPermission;
use Wave8\Factotum\Base\Models\User;

class MediaPolicy
{
    public function read(User $user): bool
    {
        return $user->hasPermissionTo(MediaPermission::READ_MEDIA);
    }

    public function upload(User $user): bool
    {
        return $user->hasPermissionTo(MediaPermission::UPLOAD_MEDIA);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(MediaPermission::DELETE_MEDIA);
    }
}
