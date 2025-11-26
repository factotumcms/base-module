<?php

namespace Wave8\Factotum\Base\Dtos\Api\Media;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class MediaPresetConfigDto extends Data
{
    public function __construct(
        public readonly string $suffix,
        public readonly array $actions,
    ) {}
}
