<?php

namespace Wave8\Factotum\Base\Enums\Notification;

use Wave8\Factotum\Base\Traits\ListCases;

enum NotificationChannel: string
{
    use ListCases;
    case EMAIL = 'email';
    case SMS = 'sms';
    case PUSH = 'push';
}
