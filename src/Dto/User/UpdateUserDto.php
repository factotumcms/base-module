<?php

namespace Wave8\Factotum\Base\Dto\User;

use Spatie\LaravelData\Data;

class UpdateUserDto extends Data
{
    public function __construct(
        public readonly ?string $first_name = null,
        public readonly ?string $last_name = null,
        public readonly bool $is_active = true,
    ) {}

    public static function make(
        ?string $first_name = null,
        ?string $last_name = null,
        bool $is_active = true,
    ): static {
        return new static(
            first_name: $first_name,
            last_name: $last_name,
            is_active: $is_active,
        );
    }
}
