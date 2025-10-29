<?php

namespace Wave8\Factotum\Base\Dtos\Api\Media;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class StoreFileDto extends Data
{
    public function __construct(
        public readonly UploadedFile $file,
        public readonly ?array $presets = null,
    ) {}
}
