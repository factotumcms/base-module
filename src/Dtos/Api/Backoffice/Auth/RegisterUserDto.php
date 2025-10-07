<?php

namespace Wave8\Factotum\Base\Dtos\Api\Backoffice\Auth;

use Spatie\LaravelData\Data;

class RegisterUserDto extends Data
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $password_confirmation,
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly ?string $username = null,
    ) {}

    public static function make(
        string $email,
        string $password,
        string $password_confirmation,
        string $first_name,
        string $last_name,
        ?string $username = null,
    ): static {
        return new static(
            email: $email,
            password: $password,
            password_confirmation: $password_confirmation,
            first_name: $first_name,
            last_name: $last_name,
            username: $username,
        );
    }
}
