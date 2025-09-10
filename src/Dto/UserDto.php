<?php

namespace Wave8\Factotum\Base\Dto;

use Spatie\LaravelData\Data;

class UserDto extends Data
{
    public function __construct(
        public string $email,
        public string $password,
        public ?string $first_name = null,
        public ?string $last_name = null,
        public ?string $username = null,
        public bool $is_active = true,
    ) {}

}
