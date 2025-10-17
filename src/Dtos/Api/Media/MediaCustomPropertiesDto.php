<?php

namespace Wave8\Factotum\Base\Dtos\Api\Media;

use Spatie\LaravelData\Data;

class MediaCustomPropertiesDto extends Data
{
    public function __construct(
        public readonly ?string $alt,
        public readonly ?string $title
    ) {}
}
