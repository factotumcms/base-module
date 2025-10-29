<?php

namespace Wave8\Factotum\Base\Policies;

use Wave8\Factotum\Base\Models\Notification;
use Wave8\Factotum\Base\Models\User;

class NotificationPolicy
{
    public function view(User $user, Notification $notification): bool
    {
        return $notification->notifiable()->is($user);
    }

    public function markAsRead(User $user, Notification $notification): bool
    {
        return true;
    }

    public function markManyAsRead(User $user): bool
    {
        if (! request()->has('ids')) {
            return true;
        }

        $ids = request()->ids;

        return $user->notifications()->whereIn('id', $ids)->count() === count($ids);
    }
}
