<?php

namespace Wave8\Factotum\Base\Resources\Api;

use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Resource;

#[MapName(SnakeCaseMapper::class)]
class UserResource extends Resource
{
    public function __construct(
        public int $id,
        public string $email,
        public ?string $firstName,
        public ?string $lastName,
        public ?string $username,
        public \DateTime $createdAt,
        public ?Collection $roles,
        public ?MediaResource $avatar = null,
    ) {}
}
