<?php

namespace Wave8\Factotum\Base\Dtos\Api\Notification;

use Spatie\LaravelData\Data;

class ReadNotificationDto extends Data
{
    public function __construct(
        public readonly bool $read,
    ) {}
}
