<?php

namespace Wave8\Factotum\Base\Observers;

use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Notifications\VerifyEmail;

class UserObserver
{
    public function __construct() {}

    /**
     * Handle the User "created" event.
     *
     * @throws \Exception
     */
    public function created(User $user): void
    {
        $user->notify(new VerifyEmail);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "User" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
