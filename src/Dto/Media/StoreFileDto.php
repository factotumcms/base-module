<?php

namespace Wave8\Factotum\Base\Dto\Media;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Enum\Disk;

class StoreFileDto extends Data
{
    public function __construct(
        public readonly UploadedFile $file,
        public readonly Disk $disk = Disk::PUBLIC,
        public readonly string $path = 'images',
        public readonly string $conversions_path = 'thumb',
    ) {}

    public static function make(
        UploadedFile $file,
        Disk $disk = Disk::PUBLIC,
        string $path = 'images',
        string $conversionsPath = 'thumb'
    ): static {
        return new static(
            file: $file,
            disk: $disk,
            path: $path,
            conversions_path: $conversionsPath

        );
    }
}
