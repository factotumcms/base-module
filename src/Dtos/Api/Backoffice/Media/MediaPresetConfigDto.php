<?php

namespace Wave8\Factotum\Base\Dtos\Api\Backoffice\Media;

use Spatie\LaravelData\Data;

class MediaPresetConfigDto extends Data
{
    public function __construct(
        public readonly string $suffix,
        public bool $optimize,
        public readonly ?MediaResizeDto $resize,
        public readonly ?MediaFitDto $fit,
        public readonly ?MediaCropDto $crop,
        // public readonly filter,
        // public readonly brightness,
        // public readonly contrast,
        // public readonly gamma,
        // public readonly orientation,
    ) {}

    public static function make(
        string $suffix,
        bool $optimize = true,
        ?MediaResizeDto $resize = null,
        ?MediaFitDto $fit = null,
        ?MediaCropDto $crop = null,
    ): static {
        return new static(
            suffix: $suffix,
            optimize: $optimize,
            resize: $resize,
            fit: $fit,
            crop: $crop,
        );
    }
}
