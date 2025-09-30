<?php

namespace Wave8\Factotum\Base\Dtos\Api\Mobile\Auth;

use Spatie\LaravelData\Data;

class LoginUserDto extends Data
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $username = null,
    ) {}

    public static function make(
        string $email,
        string $password,
        ?string $username = null,
    ): static {
        return new static(
            email: $email,
            password: $password,
            username: $username,
        );
    }
}
