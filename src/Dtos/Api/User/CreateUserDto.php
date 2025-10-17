<?php

namespace Wave8\Factotum\Base\Dtos\Api\User;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class CreateUserDto extends Data
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $username = null,
        public readonly bool $isActive = true,
    ) {}
}
