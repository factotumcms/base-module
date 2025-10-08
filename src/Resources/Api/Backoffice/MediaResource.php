<?php

namespace Wave8\Factotum\Base\Resources\Api\Backoffice;

use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Resource;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\Media\MediaType;

class MediaResource extends Resource
{
    /**
     * Create a MediaResource instance with its media metadata.
     *
     * @param int $id Primary identifier for the media resource.
     * @param string|null $uuid Optional UUID identifier.
     * @param string $name Human-readable name of the media.
     * @param string $file_name Stored filename of the media.
     * @param string $path Directory path on the disk where the file is stored.
     * @param string $mime_type MIME type of the media file.
     * @param Disk $disk Storage disk enum indicating where the file is kept.
     * @param MediaType $media_type Enum classifying the media type.
     * @param int $size Size of the file in bytes.
     * @param array|null $conversions Optional array of conversion metadata (e.g., derived versions or variants).
     */
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

    /**
     * Adds supplementary resource data including the publicly accessible URL for the media file.
     *
     * @return array{url:string} An array containing the 'url' key with the media file's public URL.
     */
    public function with(): array
    {
        return [
            'url' => Storage::disk($this->disk)->url($this->path.'/'.$this->file_name),
        ];
    }
}