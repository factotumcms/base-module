<?php

namespace Wave8\Factotum\Base\Dtos\Api\Notification;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ReadManyNotificationDto extends Data
{
    /**
     * @param  Optional|array<int>|null  $ids
     */
    public function __construct(
        public readonly Optional|null|array $ids = null,
        public readonly bool $read,
    ) {}
}
