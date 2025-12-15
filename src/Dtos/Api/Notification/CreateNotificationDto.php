<?php

namespace Wave8\Factotum\Base\Dtos\Api\Notification;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Wave8\Factotum\Base\Contracts\NotifiableInterface;
use Wave8\Factotum\Base\Enums\Notification\NotificationChannel;

#[MapName(SnakeCaseMapper::class)]
class CreateNotificationDto extends Data
{
    public function __construct(
        public readonly string $type,
        public readonly string $notifiableType,
        public readonly int $notifiableId,
        public readonly string $data,
        public readonly NotificationChannel $channel,
        public readonly string $route
    ) {}
}
