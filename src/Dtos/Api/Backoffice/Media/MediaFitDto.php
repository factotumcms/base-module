<?php

namespace Wave8\Factotum\Base\Dtos\Api\Backoffice\Media;

use Spatie\Image\Enums\Fit;
use Spatie\LaravelData\Data;

class MediaFitDto extends Data
{
    /**
     * Initialize the DTO with a fit method and target dimensions.
     *
     * @param Fit $method The fit method to apply when resizing/cropping.
     * @param int $width Target width in pixels.
     * @param int $height Target height in pixels.
     */
    public function __construct(
        public readonly Fit $method,
        public readonly int $width,
        public readonly int $height,
    ) {}
}