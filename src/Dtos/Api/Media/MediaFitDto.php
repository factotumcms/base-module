<?php

namespace Wave8\Factotum\Base\Dtos\Api\Media;

use Spatie\Image\Enums\Fit;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class MediaFitDto extends Data
{
    public function __construct(
        public readonly Fit $method,
        public readonly int $width,
        public readonly int $height,
    ) {}
}
