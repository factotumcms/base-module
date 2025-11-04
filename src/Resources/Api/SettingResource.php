<?php

namespace Wave8\Factotum\Base\Resources\Api;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Resource;
use Wave8\Factotum\Base\Enums\Setting\SettingDataType;

#[MapName(SnakeCaseMapper::class)]
class SettingResource extends Resource
{
    public function __construct(
        public int $id,
        public SettingDataType $dataType,
        public ?string $key,
        public string|int|float|array $value,
        public ?string $description,
    ) {}
}
