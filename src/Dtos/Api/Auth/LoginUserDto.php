<?php

namespace Wave8\Factotum\Base\Dtos\Api\Auth;

use Spatie\LaravelData\Data;

class LoginUserDto extends Data
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $username = null,
    ) {}
}
