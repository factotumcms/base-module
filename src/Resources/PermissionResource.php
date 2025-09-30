<?php

namespace Wave8\Factotum\Base\Resources;

use Spatie\LaravelData\Resource;

class PermissionResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        //        public ?string $guard_name
    ) {}
}
