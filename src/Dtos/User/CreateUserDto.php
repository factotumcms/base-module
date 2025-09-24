<?php

namespace Wave8\Factotum\Base\Dtos\User;

use Spatie\LaravelData\Data;

class CreateUserDto extends Data
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $first_name = null,
        public readonly ?string $last_name = null,
        public readonly ?string $username = null,
        public readonly bool $is_active = true,
    ) {}

    public static function make(
        string $email,
        string $password,
        ?string $first_name = null,
        ?string $last_name = null,
        ?string $username = null,
        bool $is_active = true,
    ): static {
        return new static(
            email: $email,
            password: $password,
            first_name: $first_name,
            last_name: $last_name,
            username: $username,
            is_active: $is_active,
        );
    }
}
