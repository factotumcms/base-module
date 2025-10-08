<?php

namespace Wave8\Factotum\Base\Resources\Api\Backoffice;

use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Resource;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\Media\MediaType;

class MediaResource extends Resource
{
    public function __construct(
        public int $id,
        public ?string $uuid,
        public string $name,
        public string $file_name,
        public string $path,
        public string $mime_type,
        public Disk $disk,
        public MediaType $media_type,
        public int $size,
        public ?array $conversions,
    ) {}

    public function with(): array
    {
        return [
            'url' => Storage::disk($this->disk)->url($this->path.'/'.$this->file_name),
        ];
    }
}
