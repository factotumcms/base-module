<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Services\MediaServiceInterface;
use Wave8\Factotum\Base\Dto\Media\CreateImageDto;
use Wave8\Factotum\Base\Dto\Media\StoreFileDto;
use Wave8\Factotum\Base\Models\Media;

class MediaService implements MediaServiceInterface
{
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
            $this->create(
                data: CreateImageDto::make(
                    name: $metadata['original_filename'],
                    file_name: $metadata['filename'],
                    mime_type: $metadata['mime_type'],
                    disk: $data->disk,
                    path: $data->path,
                    conversions_disk: $data->disk,
                    conversions_path: $data->conversions_path,
                    size: $metadata['size'],
                )
            );
        }

        return $storedFilename;
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
     * @throws \Exception
     */
    private function checkFileNameConflict(string $filename): void
    {
        $media = Media::where('file_name', $filename)->first();

        if ($media) {
            throw new \Exception('File name conflict: '.$filename);
        }
    }

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
        // TODO: Implement filter() method.
    }
}
