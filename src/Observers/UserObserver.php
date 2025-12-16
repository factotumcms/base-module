<?php

namespace Wave8\Factotum\Base\Observers;

use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Notification\CreateNotificationDto;
use Wave8\Factotum\Base\Enums\Notification\NotificationChannel;
use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Jobs\SendEmailNotifications;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Notifications\VerifyEmail;
use Wave8\Factotum\Base\Services\Api\SettingService;

class UserObserver
{
    public function __construct(
        /** @var SettingService $settingService */
        private SettingServiceInterface $settingService,
    ) {}

    /**
     * Handle the User "created" event.
     *
     * @throws \Exception
     */
    public function created(User $user): void
    {
        if ($this->settingService->getValue(key: Setting::ENABLE_USER_VERIFY_EMAIL, group: SettingGroup::NOTIFICATIONS)) {
            $dto = new CreateNotificationDto(
                type: VerifyEmail::class,
                channel: NotificationChannel::EMAIL,
            );

            $notification = $user->notifications()->create($dto->toArray());

            SendEmailNotifications::dispatch(
                notification: $notification,
            )->onQueue('notifications');
        }
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
