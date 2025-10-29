<?php

namespace Wave8\Factotum\Base\Dtos\Api\Media;

use Spatie\Image\Enums\CropPosition;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class MediaCropDto extends Data
{
    public function __construct(
        public readonly int $width,
        public readonly int $height,
        public readonly ?CropPosition $position = CropPosition::Center,
    ) {}
}
