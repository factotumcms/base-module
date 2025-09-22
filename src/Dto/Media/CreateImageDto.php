<?php

namespace Wave8\Factotum\Base\Dto\Media;

use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Enum\Disk;
use Wave8\Factotum\Base\Enum\MediaType;

class CreateImageDto extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $file_name,
        public readonly string $mime_type,
        public readonly MediaType $media_type,
        public readonly Disk $disk,
        public readonly string $path,
        public readonly Disk $conversions_disk,
        public readonly string $conversions_path,
        public readonly int $size,
        public readonly ?array $custom_properties = null,
    ) {}

    public static function make(
        string $name,
        string $file_name,
        string $mime_type,
        MediaType $media_type,
        Disk $disk,
        string $path,
        Disk $conversions_disk,
        string $conversions_path,
        int $size,
        ?array $custom_properties = null,
    ): static {
        return new static(
            name: $name,
            file_name: $file_name,
            mime_type: $mime_type,
            media_type: $media_type,
            disk: $disk,
            path: $path,
            conversions_disk: $conversions_disk,
            conversions_path: $conversions_path,
            size: $size,
            custom_properties: $custom_properties,
        );
    }
}
