<?php

namespace Wave8\Factotum\Base\Dtos\Api\Auth;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class LoginUserDto extends Data
{
    public string|Optional $username;

    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}
}
