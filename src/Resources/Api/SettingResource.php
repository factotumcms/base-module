<?php

namespace Wave8\Factotum\Base\Resources\Api;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Resource;

#[MapName(SnakeCaseMapper::class)]
class SettingResource extends Resource
{
    public function __construct(
        public string $dataType,
        public ?string $key,
        public ?string $value,
        public ?string $description,
    ) {}
}
