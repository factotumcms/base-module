<?php

namespace Wave8\Factotum\Base\Dtos\Api\Notification;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Wave8\Factotum\Base\Contracts\NotifiableInterface;

#[MapName(SnakeCaseMapper::class)]
class CreateNotificationDto extends Data
{
    public function __construct(
        public readonly string $type,
        public NotifiableInterface $notifiable,
        public readonly string $data,
        public readonly string $channel,
        public readonly string $route,
        public readonly string $lang,
    ) {}
}
