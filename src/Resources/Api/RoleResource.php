<?php

namespace Wave8\Factotum\Base\Resources\Api;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Resource;

#[MapName(SnakeCaseMapper::class)]
class RoleResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $guardName
    ) {}
}
