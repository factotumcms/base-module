<?php

namespace Wave8\Factotum\Base\Dto\Media;

use Spatie\LaravelData\Data;

class MediaCustomProperties extends Data
{
    public function __construct(
        public readonly ?string $alt,
        public readonly ?string $title,
        public readonly ?array $conversions = [],
    ) {}

    public static function make(
        ?string $alt = null,
        ?string $title = null,
        ?array $conversions = [],
    ): static {
        return new static(
            alt: $alt,
            title: $title,
            conversions: $conversions,
        );
    }
}
