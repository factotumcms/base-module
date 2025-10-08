<?php

namespace Wave8\Factotum\Base\Dtos\Api\Backoffice\Media;

use Spatie\Image\Enums\Fit;
use Spatie\LaravelData\Data;

class MediaResizeDto extends Data
{
    public function __construct(
        public readonly int $width,
        public readonly int $height,
    ) {}
}
