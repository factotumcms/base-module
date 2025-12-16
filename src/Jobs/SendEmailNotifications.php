<?php

namespace Wave8\Factotum\Base\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Wave8\Factotum\Base\Models\Notification;
use Wave8\Factotum\Base\Models\User;

class SendEmailNotifications implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Notification $notification
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var User $user */
        $user = $this->notification->notifiable;

        $user->notify(new $this->notification->type);

        $this->notification->route = $user->email;
        $this->notification->sent_at = now();

        $this->notification->save();

        Log::info("Sent {$this->notification->type} to notifiable ID {$user->id}");
    }
}
