<?php

namespace Wave8\Factotum\Base\Dtos\Api\Permission;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class CreatePermissionDto extends Data
{
    public string|Optional $guardName;

    public function __construct(
        public readonly string $name,
    ) {
        $this->guardName = 'web';
    }
}
