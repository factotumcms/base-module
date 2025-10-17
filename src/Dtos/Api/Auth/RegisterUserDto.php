<?php

namespace Wave8\Factotum\Base\Dtos\Api\Auth;

use Illuminate\Support\Str;
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
        $this->username = Str::slug($this->firstName.$this->lastName.rand(1, 50));
    }
}
