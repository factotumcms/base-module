<?php

namespace Wave8\Factotum\Base\Dtos\Api\Backoffice\Media;

use Spatie\LaravelData\Data;

class MediaPresetConfigDto extends Data
{
    public function __construct(
        public readonly ?string $width,
        public readonly ?string $height,
        public readonly ?string $fit,
    ) {}

    public static function make(
        ?string $width = null,
        ?string $height = null,
        ?string $fit = null,

    ): static {
        return new static(
            width: $width,
            height: $height,
            fit: $fit,
        );
    }
}
