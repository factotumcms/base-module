<?php

namespace Wave8\Factotum\Base\Dtos\Api\Backoffice\Media;

use Spatie\Image\Enums\Fit;
use Spatie\LaravelData\Data;

class MediaFitDto extends Data
{
    public function __construct(
        public readonly Fit $method,
        public readonly int $width,
        public readonly int $height,
    ) {}
}
