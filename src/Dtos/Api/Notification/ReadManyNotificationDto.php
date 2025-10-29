<?php

namespace Wave8\Factotum\Base\Dtos\Api\Notification;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class ReadManyNotificationDto extends Data
{
    public function __construct(
        public readonly Optional|null|array $ids = null,
        public readonly bool $read,
    ) {}
}
