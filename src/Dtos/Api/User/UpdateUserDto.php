<?php

namespace Wave8\Factotum\Base\Dtos\Api\User;

use Spatie\LaravelData\Data;

class UpdateUserDto extends Data
{
    public function __construct(
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly bool $isActive = true,
    ) {}
}
