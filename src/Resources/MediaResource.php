<?php

namespace Wave8\Factotum\Base\Resources;

use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Resource;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\MediaType;

class MediaResource extends Resource
{
    public function __construct(
        public int $id,
        public ?string $uuid,
        public string $name,
        public string $file_name,
        public string $mime_type,
        public Disk $disk,
        public MediaType $media_type,
        public int $size,
    ) {
        $this->file_name = url(Storage::disk($disk)->url($this->file_name));
    }

}
