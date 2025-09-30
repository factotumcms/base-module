<?php

namespace Wave8\Factotum\Base\Services\Api\Backoffice;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Image\Image;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\MediaServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\SettingServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\Media\CreateMediaDto;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\Media\MediaCustomPropertiesDto;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\Media\StoreFileDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\MediaPreset;
use Wave8\Factotum\Base\Enums\MediaType;
use Wave8\Factotum\Base\Enums\Setting;
use Wave8\Factotum\Base\Jobs\GenerateImagesConversions;
use Wave8\Factotum\Base\Models\Media;
use Wave8\Factotum\Base\Traits\Filterable;
use Wave8\Factotum\Base\Traits\Sortable;

class MediaService implements MediaServiceInterface
{
    use Filterable, Sortable;

    public function __construct(
        /** @var SettingService $settingService */
        private readonly SettingServiceInterface $settingService,
    ) {}

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
        $media = Media::findOrFail($id);
        $media->update($data->toArray());

        return $media;
    }

    public function delete(int $id): bool
    {
        return true;
    }

    public function getAll(): Collection
    {
        return Media::all();
    }

    public function filter(QueryFiltersDto $queryFilters): Paginator|LengthAwarePaginator
    {
        $query = Media::query();

        $this->applyFilters($query, $queryFilters->search);
        $this->applySorting($query, $queryFilters);

        return $query->simplePaginate(
            perPage: $queryFilters->perPage ?? 15,
            page: $queryFilters->page
        );
    }

    /**
     * @throws \Exception
     */
    public function store(StoreFileDto $data): bool|string
    {
        $metadata = $this->generateFileMetadata($data->file);
        $presetConfigs = $this->getPresetsConfigs($data);
        $mediaBasePath = $this->generateMediaPath();
        $disk = Disk::tryFrom($this->settingService->getSystemSettingValue(Setting::DEFAULT_MEDIA_DISK));

        $this->checkMediaUnique($metadata['filename'], $disk->value, $mediaBasePath);

        $storedFilename = $data->file->storeAs(
            path: $mediaBasePath,
            name: $metadata['filename'],
            options: ['disk' => $disk->value]
        );

        if ($storedFilename) {
            $this->create(
                data: CreateMediaDto::make(
                    name: $metadata['original_filename'],
                    file_name: $metadata['filename'],
                    mime_type: $metadata['mime_type'],
                    media_type: $this->detectMediaType($metadata['mime_type']),
                    presets: json_encode(array_keys($presetConfigs)),
                    disk: $disk,
                    path: $mediaBasePath,
                    size: $metadata['size'],
                    custom_properties: json_encode($this->setDefaultCustomProperties($metadata))
                )
            );

            GenerateImagesConversions::dispatch();
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
            'application/msword' => MediaType::FILE,
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => MediaType::FILE,
            default => throw new \Exception('Unsupported media type: '.$mimeType),
        };

    }

    private function generateFileMetadata(UploadedFile $file): array
    {
        $metadata = [
            'mime_type' => $file->getMimeType(),
            'original_filename' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
        ];

        $metadata['extension'] = $file->getClientOriginalExtension();

        $tempFilename = Str::slug(File::name($metadata['original_filename']));

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
        foreach (json_decode($media->presets) as $preset) {

            match ($preset) {
                MediaPreset::PROFILE_PICTURE->value => $path = $this->generateProfilePicture($media),
                MediaPreset::THUMBNAIL->value => $path = $this->generateImageThumbnail($media),
            };

            $conversions[$preset] = $path;
        }

        $media->conversions = json_encode($conversions);
        $media->save();
    }

    private function generateImageThumbnail(Model $media): string
    {
        $fileName = File::name($media->file_name);
        $fileExtension = '.'.File::extension($media->file_name);

        $fullMediaPath = $this->getFullMediaPath($media);
        $fullMediaDirectory = Storage::disk($media->disk)->path($media->path);

        $destPath = $fullMediaDirectory.'/conversions';

        if (! is_dir($destPath)) {
            File::makeDirectory($destPath, 0755, true);
        }

        $thumbSuffix = '_thumb';
        $destPath .= '/'.$fileName.$thumbSuffix.$fileExtension;

        if (is_file($fullMediaPath)) {
            $props = json_decode($this->settingService->getSystemSettingValue(Setting::PROFILE_PICTURE_PRESET)); // todo change to thumbnail preset

            try {

                Image::load($fullMediaPath)
                    ->width($props->width)
                    ->height($props->height)
//                    ->fit($props['fit'], $props['position'])
                    ->save($destPath);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

        return $destPath;
    }

    private function setDefaultCustomProperties(array $metadata): MediaCustomPropertiesDto
    {
        switch ($this->detectMediaType($metadata['mime_type'])) {
            case MediaType::IMAGE:
                return MediaCustomPropertiesDto::make(
                    alt: $metadata['original_filename'],
                    title: $metadata['original_filename'],
                );

            default:
                return MediaCustomPropertiesDto::make();

        }
    }

    private function getPresetsConfigs($data): array
    {
        $configs = [];

        foreach ($data->presets as $preset) {

            $config = $this->settingService->getSystemSettingValue(Setting::from($preset->value));
            if (isset($config)) {
                $configs[$preset->value] = $config;
            }
        }

        return $configs;
    }

    private function generateMediaPath(): string
    {
        $basePath = $this->settingService->getSystemSettingValue(Setting::MEDIA_BASE_PATH);

        return implode(DIRECTORY_SEPARATOR, [
            $basePath, date('Y'), date('m'), date('d'),
        ]);

    }

    private function generateProfilePicture(Model $media): string
    {
        return 'media/toimplement.jpg';
    }
}
