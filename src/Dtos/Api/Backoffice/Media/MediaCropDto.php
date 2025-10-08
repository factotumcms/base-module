<?php

namespace Wave8\Factotum\Base\Dtos\Api\Backoffice\Media;

use Spatie\Image\Enums\CropPosition;
use Spatie\LaravelData\Data;

class MediaCropDto extends Data
{
    public function __construct(
        public readonly int $width,
        public readonly int $height,
        public readonly ?CropPosition $position = CropPosition::Center,
    ) {}
}
