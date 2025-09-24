<?php

namespace Wave8\Factotum\Base\Dtos\Media;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class StoreFileDto extends Data
{
    public function __construct(
        public readonly UploadedFile $file,
        public readonly ?array $presets = null,
    ) {}

    public static function make(
        UploadedFile $file,
        ?array $presets = null,
    ): static {
        return new static(
            file: $file,
            presets: $presets,
        );
    }
}
