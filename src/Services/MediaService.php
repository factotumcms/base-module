<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Orientation;
use Spatie\Image\Image;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Services\MediaServiceInterface;
use Wave8\Factotum\Base\Contracts\Services\SettingServiceInterface;
use Wave8\Factotum\Base\Dto\Media\CreateImageDto;
use Wave8\Factotum\Base\Dto\Media\MediaCustomProperties;
use Wave8\Factotum\Base\Dto\Media\StoreFileDto;
use Wave8\Factotum\Base\Enum\MediaType;
use Wave8\Factotum\Base\Enum\Setting;
use Wave8\Factotum\Base\Jobs\GenerateImagesConversions;
use Wave8\Factotum\Base\Models\Media;

class MediaService implements MediaServiceInterface
{
    function __construct(
        /** @var SettingService $settingService */
        private readonly SettingServiceInterface $settingService,
    )
    {

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

        $this->checkMediaUnique($metadata['filename'], $data->disk->value, $data->path);

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
                    custom_properties: $this->setDefaultCustomProperties($metadata)
                )
            );

            if ($media) {
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
    private function checkMediaUnique(string $filename, string $disk, string $path): void
    {
        $media = Media::where('file_name', $filename)
            ->where('disk', $disk)
            ->where('path', $path)
            ->first();

        if ($media) {
            throw new \Exception('File name conflict: '.$filename);
        }
    }

    public function generateConversions(Model $media): void
    {
        $props = json_decode($media->custom_properties);

        if($props->conversions->thumbnail->enabled){
            $this->generateImageThumbnail($media);
        }



//        $media->converted = true;
        $media->save();
    }

    private function generateImageThumbnail(Model $media): void
    {
//        $basePath = Storage::disk($media->disk)->path($media->path);
        $fileName = explode('.',  $media->file_name)[0];
        $fileExtension = '.' . explode('.',  $media->file_name)[1];
        $fullPath = $this->getFullMediaPath($media);
        $props = json_decode($media->custom_properties);

        $destPath = Storage::disk($media->conversions_disk)->path($media->conversions_path);

        if($props->conversions->thumbnail->path){
            $destPath .= '/'.$props->conversions->thumbnail->path;
        }

        if(!is_dir($destPath)){
            File::makeDirectory($destPath, 0755, true);
        }

        $thumbSuffix = $this->settingService->getSystemSettingValue(Setting::THUMB_SUFFIX);
        $destPath .= '/'.$fileName . $thumbSuffix . $fileExtension;

        if(is_file($fullPath)){
            $width = $this->settingService->getSystemSettingValue(Setting::THUMB_SIZE_WIDTH);
            $height = $this->settingService->getSystemSettingValue(Setting::THUMB_SIZE_HEIGHT);

            try {

                Image::load($fullPath)
                    ->width($width)
                    ->height($height)
                    ->save($destPath);

            }catch (\Exception $e){
                dd($e->getMessage());
            }
        }
    }

    private function setDefaultCustomProperties(array $metadata): MediaCustomProperties
    {
        switch ($this->detectMediaType($metadata['mime_type'])) {
            case MediaType::IMAGE:
                return MediaCustomProperties::make(
                    alt: $metadata['original_filename'],
                    title: $metadata['original_filename'],
                    conversions: [
                        'thumbnail' => [
                            'enabled' => true,
                            //todo:: usare config
                            'path' => 'thumb',
                        ],
                        'greyscale' => false,
                        'blur' => false,
                    ]
                );

            default:
                return MediaCustomProperties::make();

        }
    }
}
