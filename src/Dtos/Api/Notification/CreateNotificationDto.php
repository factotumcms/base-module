<?php

namespace Wave8\Factotum\Base\Dtos\Api\Notification;

use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;
use Wave8\Factotum\Base\Enums\Notification\NotificationChannel;

#[MapName(SnakeCaseMapper::class)]
class CreateNotificationDto extends Data
{
    public string $id;

    public function __construct(
        public readonly string $type,
        public readonly NotificationChannel $channel,
        public readonly Optional|string $data = '',
        public readonly Optional|string $route = ''
    ) {
        $this->id = Str::uuid()->toString();
    }
}
