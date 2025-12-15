<?php

namespace Wave8\Factotum\Base\Observers;

use Wave8\Factotum\Base\Contracts\Api\NotificationServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Notification\CreateNotificationDto;
use Wave8\Factotum\Base\Enums\Notification\NotificationChannel;
use Wave8\Factotum\Base\Jobs\SendEmailNotifications;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Notifications\VerifyEmail;
use Wave8\Factotum\Base\Services\Api\NotificationService;

class UserObserver
{
    public function __construct(
        /** @var NotificationService $notificationService */
        private NotificationServiceInterface $notificationService,
    ) {}

    /**
     * Handle the User "created" event.
     *
     * @throws \Exception
     */
    public function created(User $user): void
    {
        $this->notificationService->create(
            new CreateNotificationDto(
                type: VerifyEmail::class,
                notifiableType: User::class,
                notifiableId: $user->id,
                data: '',
                channel: NotificationChannel::EMAIL,
                route: ''
            )
        );
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
