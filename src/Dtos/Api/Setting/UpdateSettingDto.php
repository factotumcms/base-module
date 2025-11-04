<?php

namespace Wave8\Factotum\Base\Dtos\Api\Setting;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class UpdateSettingDto extends Data
{
    public function __construct(
        public ?string $value,
        public Optional|string|null $description,
    ) {}
}
