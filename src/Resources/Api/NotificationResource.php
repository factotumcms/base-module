<?php

namespace Wave8\Factotum\Base\Resources\Api;

use Spatie\LaravelData\Resource;

class NotificationResource extends Resource
{
    public function __construct(
        public int $id,
        public string $type,
        public array $data,
        public string $channel,
        public string $route,
        public string $lang,
        public ?\DateTime $sentAt = null,
        public ?string $response,
        public ?\DateTime $readAt = null,
        public ?\DateTime $createdAt = null,
        public ?\DateTime $updatedAt = null,
    ) {}
}
