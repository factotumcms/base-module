<?php

namespace Wave8\Factotum\Base\Dtos\Api\Auth;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class RegisterUserDto extends Data
{
    public string|Optional $username;

    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $passwordConfirmation,
        public readonly string $firstName,
        public readonly string $lastName,
    ) {
        $this->username = trim(strtolower(str_replace(' ', '', $this->firstName))).'.'.trim(strtolower(str_replace(' ', '', $this->lastName)));
    }
}
