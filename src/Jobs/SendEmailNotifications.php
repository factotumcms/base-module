<?php

namespace Wave8\Factotum\Base\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Wave8\Factotum\Base\Contracts\Api\NotificationServiceInterface;
use Wave8\Factotum\Base\Enums\Notification\NotificationChannel;
use Wave8\Factotum\Base\Services\Api\NotificationService;

class SendEmailNotifications
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var NotificationService $notificationService */
        $notificationService = app(NotificationServiceInterface::class);

        foreach ($notificationService->getPendingsByChannel(NotificationChannel::EMAIL) as $notification) {
            $notificationService->elaborate($notification);
        }
    }
}
