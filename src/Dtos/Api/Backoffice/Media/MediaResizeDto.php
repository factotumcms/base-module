<?php

namespace Wave8\Factotum\Base\Dtos\Api\Backoffice\Media;

use Spatie\LaravelData\Data;

class MediaResizeDto extends Data
{
    /**
     * Create a DTO representing target dimensions for media resizing.
     *
     * @param int $width Target width in pixels.
     * @param int $height Target height in pixels.
     */
    public function __construct(
        public readonly int $width,
        public readonly int $height,
    ) {}
}