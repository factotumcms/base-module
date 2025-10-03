<?php

namespace Wave8\Factotum\Base\Dtos\Api\Backoffice\Media;

use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\Media\MediaType;

class CreateMediaDto extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $file_name,
        public readonly string $mime_type,
        public readonly MediaType $media_type,
        public readonly ?string $presets,
        public readonly Disk $disk,
        public readonly string $path,
        public readonly int $size,
        public readonly string $custom_properties,
        public readonly ?string $conversions = null,
    ) {}

    public static function make(
        string $name,
        string $file_name,
        string $mime_type,
        MediaType $media_type,
        ?string $presets,
        Disk $disk,
        string $path,
        int $size,
        string $custom_properties,
        ?string $conversions = null,
    ): static {
        return new static(
            name: $name,
            file_name: $file_name,
            mime_type: $mime_type,
            media_type: $media_type,
            presets: $presets,
            disk: $disk,
            path: $path,
            size: $size,
            custom_properties: $custom_properties,
            conversions: $conversions,
        );
    }
}
