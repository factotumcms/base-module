<?php

namespace Wave8\Factotum\Base\Resources\Api\Backoffice;

use Spatie\LaravelData\Resource;

class RoleResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $guard_name
    ) {}
}
