<?php

namespace Wave8\Factotum\Base\Resources\Api;

use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Resource;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\Media\MediaType;

#[MapName(SnakeCaseMapper::class)]
class MediaResource extends Resource
{
    public function __construct(
        public int $id,
        public ?string $uuid,
        public string $name,
        public string $fileName,
        public string $path,
        public string $mimeType,
        public Disk $disk,
        public MediaType $mediaType,
        public int $size,
        public ?array $conversions,
    ) {}

    /**
     * Adds supplementary resource data including the publicly accessible URL for the media file.
     *
     * @return array{url:string} An array containing the 'url' key with the media file's public URL.
     */
    public function with(): array
    {
        return [
            'url' => Storage::disk($this->disk->value)->url($this->path.'/'.$this->file_name),
        ];
    }
}
