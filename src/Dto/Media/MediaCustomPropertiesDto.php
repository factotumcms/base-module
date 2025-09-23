<?php

namespace Wave8\Factotum\Base\Dto\Media;

use Spatie\LaravelData\Data;

class MediaCustomPropertiesDto extends Data
{
    public function __construct(
        public readonly ?string $alt,
        public readonly ?string $title
    ) {}

    public static function make(
        ?string $alt = null,
        ?string $title = null
    ): static {
        return new static(
            alt: $alt,
            title: $title
        );
    }
}
