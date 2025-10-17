<?php

namespace Wave8\Factotum\Base\Dtos\Api\Media;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class StoreFileDto extends Data
{
    public function __construct(
        public readonly UploadedFile $file,
        public readonly ?array $presets = null,
    ) {}
}
