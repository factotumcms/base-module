<?php

namespace Wave8\Factotum\Base\Resources\Api;

use Spatie\LaravelData\Resource;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapName;
#[MapName(SnakeCaseMapper::class)]
class RoleResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $guardName
    ) {}
}
