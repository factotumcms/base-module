<?php

namespace Wave8\Factotum\Base\Dtos\Api\Role;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class CreateRoleDto extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $guardName = 'web'
    ) {}
}
