<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Orientation;
use Spatie\Image\Image;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Services\MediaServiceInterface;
use Wave8\Factotum\Base\Dto\Media\CreateImageDto;
use Wave8\Factotum\Base\Dto\Media\StoreFileDto;
use Wave8\Factotum\Base\Enum\MediaType;
use Wave8\Factotum\Base\Jobs\GenerateImagesConversions;
use Wave8\Factotum\Base\Models\Media;

class MediaService implements MediaServiceInterface
{
    public function create(Data $data): Model
    {

        return Media::create($data->toArray());
    }

    public function show(int $id): ?Model
    {
        return Media::findOrFail($id);
    }

    public function update(int $id, Data $data): Model
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id): bool
    {
        // TODO: Implement delete() method.
    }

    public function getAll(): Collection
    {
        // TODO: Implement getAll() method.
    }

    public function filter(array $filters): Collection
    {
        $query = Media::query();

        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get();
    }

    /**
     * @throws \Exception
     */
    public function store(StoreFileDto $data): bool|string
    {
        $metadata = $this->generateFileMetadata($data->file);

        $this->checkFileNameConflict($metadata['filename']);

        $storedFilename = $data->file->storeAs(
            path: $data->path,
            name: $metadata['filename'],
            options: ['disk' => $data->disk->value]
        );

        if ($storedFilename) {
            $media = $this->create(
                data: CreateImageDto::make(
                    name: $metadata['original_filename'],
                    file_name: $metadata['filename'],
                    mime_type: $metadata['mime_type'],
                    media_type: $this->detectMediaType($metadata['mime_type']),
                    disk: $data->disk,
                    path: $data->path,
                    conversions_disk: $data->disk,
                    conversions_path: $data->conversions_path,
                    size: $metadata['size'],
                )
            );

            if($media){
//                GenerateImagesConversions::dispatch();
            }
        }

        return $storedFilename;
    }

    public function getFullMediaPath(Model $media): string
    {
        return Storage::disk($media->disk)->path($media->path.'/'.$media->file_name);
    }

    private function detectMediaType(string $mimeType): MediaType
    {
        return match ($mimeType) {
            'image/jpeg' => MediaType::IMAGE,
            'image/png' => MediaType::IMAGE,
            'image/gif' => MediaType::IMAGE,
            'video/mp4' => MediaType::VIDEO,
            'audio/mpeg' => MediaType::AUDIO,
            'audio/wav' => MediaType::AUDIO,
            'application/pdf' => MediaType::PDF,
            'application/msword' => MediaType::WORD,
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => MediaType::XLSX,
            default => throw new \Exception('Unsupported media type: '.$mimeType),
        };

    }

    private function generateFileMetadata(UploadedFile $file): array
    {
        $metadata = [
            'mime_type' => $file->getClientMimeType(),
            'original_filename' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
        ];

        $metadata['extension'] = $file->getClientOriginalExtension();

        $tempFilename = Str::slug(explode('.', $metadata['original_filename'])[0]);

        $metadata['filename'] = $tempFilename.'.'.$metadata['extension'];

        return $metadata;
    }

    /**
     * Checks if a file with the same name already exists in the database.
     * Throws an exception if a conflict is found to prevent overwriting existing files.
     *
     * @throws \Exception
     */
    private function checkFileNameConflict(string $filename): void
    {
        $media = Media::where('file_name', $filename)->first();

        if ($media) {
            throw new \Exception('File name conflict: '.$filename);
        }
    }

    public function generateConversions(Model $media): void
    {

        $this->generateImageThumbnail($this->getFullMediaPath($media));

    }

    private function generateImageThumbnail(string $filePath): void
    {
        Image::load($filePath)
            ->orientation(Orientation::Rotate90)
            ->width(100)
            ->height(100)
            ->save($filePath);
    }
}
