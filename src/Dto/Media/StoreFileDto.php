<?php

namespace Wave8\Factotum\Base\Dto\Media;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Enum\Disk;

class StoreFileDto extends Data
{
    public function __construct(
        public readonly UploadedFile $file,
        public readonly ?Disk $disk = Disk::PUBLIC,
        public readonly ?string $path,
        public readonly ?string $conversions_path,
    ) {}

    public static function make(
        UploadedFile $file,
        ?Disk $disk,
        ?string $path,
        ?string $conversions_path
    ): static {
        return new static(
            file: $file,
            disk: $disk ?? Disk::PUBLIC,
            path: $path ?? 'media',
            conversions_path: $conversions_path ?? 'media/conversions'

        );
    }
}
