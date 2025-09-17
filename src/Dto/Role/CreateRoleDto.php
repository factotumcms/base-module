<?php

namespace Wave8\Factotum\Base\Dto\Role;

use Spatie\LaravelData\Data;

class CreateRoleDto extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $guard_name = 'web'
    ) {}

    public static function make(
        string $name,
        string $guard_name = 'web'
    ): static {
        return new static(
            $name,
            $guard_name
        );
    }
}
