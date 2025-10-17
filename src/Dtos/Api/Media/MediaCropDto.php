<?php

namespace Wave8\Factotum\Base\Dtos\Api\Media;

use Spatie\Image\Enums\CropPosition;
use Spatie\LaravelData\Data;

class MediaCropDto extends Data
{
    /**
     * Create a MediaCropDto representing crop dimensions and position.
     *
     * @param  int  $width  The crop width in pixels.
     * @param  int  $height  The crop height in pixels.
     * @param  CropPosition|null  $position  The crop anchor position; defaults to CropPosition::Center.
     */
    public function __construct(
        public readonly int $width,
        public readonly int $height,
        public readonly ?CropPosition $position = CropPosition::Center,
    ) {}
}
