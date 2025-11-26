<?php

namespace Wave8\Factotum\Base\Dtos\Api\Media;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\Media\MediaType;

#[MapName(SnakeCaseMapper::class)]
class CreateMediaDto extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $fileName,
        public readonly string $mimeType,
        public readonly MediaType $mediaType,
        public readonly array $presets = [],
        public readonly Disk $disk,
        public readonly string $path,
        public readonly int $size,
        public readonly MediaCustomPropertiesDto $customProperties,
        public readonly ?string $conversions = null,
    ) {}
}
