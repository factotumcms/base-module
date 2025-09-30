<?php

namespace Wave8\Factotum\Base\Dtos\Api\Backoffice\Role;

use Spatie\LaravelData\Data;

class UpdateRoleDto extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $guard_name
    ) {}

    public static function make(
        string $name,
        string $guard_name
    ): static {
        return new static(
            $name,
            $guard_name
        );
    }
}
