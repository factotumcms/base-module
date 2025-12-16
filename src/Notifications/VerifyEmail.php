<?php

namespace Wave8\Factotum\Base\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as LaravalVerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends LaravalVerifyEmail
{
    use Queueable;

    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(__('auth.email_verification.subject'))
            ->greeting(__('auth.email_verification.greeting'))
            ->line(__('auth.email_verification.line_pre_cta'))
            ->action(__('auth.email_verification.cta'), $url)
            ->salutation(__('auth.email_verification.salutation', ['appName' => config('app.name')]))
            ->line(__('auth.email_verification.line_post_cta'));
    }
}
