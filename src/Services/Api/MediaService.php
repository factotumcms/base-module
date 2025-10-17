<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Image\Enums\CropPosition;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\MediaServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\SettingServiceInterface;
use Wave8\Factotum\Base\Contracts\FilterableInterface;
use Wave8\Factotum\Base\Contracts\SortableInterface;
use Wave8\Factotum\Base\Dtos\Api\Media\CreateMediaDto;
use Wave8\Factotum\Base\Dtos\Api\Media\MediaCustomPropertiesDto;
use Wave8\Factotum\Base\Dtos\Api\Media\StoreFileDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\Media\MediaType;
use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Jobs\GenerateImagesConversions;
use Wave8\Factotum\Base\Models\Media;

class MediaService implements FilterableInterface, MediaServiceInterface, SortableInterface
{
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
     * Store an uploaded file to the configured media disk, create a Media record, and dispatch conversion generation.
     *
     * @param  StoreFileDto  $data  DTO containing the uploaded file and optional preset selections.
     * @return Media Stored media model instance; throws on storage failure.
     *
     * @throws \Exception If a media record with the same filename already exists or if the file's MIME type is unsupported.
     */
    public function store(StoreFileDto $data): Media
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

        if (! $storedFilename) {
            throw new \Exception('Failed to store file: '.$metadata['original_filename']);
        }

        $media = $this->create(
            data: new CreateMediaDto(
                name: $metadata['original_filename'],
                fileName: $metadata['filename'],
                mimeType: $metadata['mime_type'],
                mediaType: $this->detectMediaType($metadata['mime_type']),
                presets: json_encode(array_keys($presetConfigs)),
                disk: $disk,
                path: $mediaBasePath,
                size: $metadata['size'],
                customProperties: json_encode($this->setDefaultCustomProperties($metadata))
            )
        );

        GenerateImagesConversions::dispatch();

        return $media;
    }

    /**
     * Map a MIME type string to the corresponding MediaType enum.
     *
     * @param  string  $mimeType  The MIME type to map (e.g., "image/jpeg").
     * @return MediaType The matched MediaType enum value.
     *
     * @throws \Exception If the MIME type is not supported.
     */
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

    /**
     * Generate and save image conversions for each preset declared on the given media and persist their public URLs.
     *
     * For each preset listed in the media's `presets` JSON, this method loads the preset configuration from system
     * settings, produces a converted image (applying resize, fit, crop, and optional optimization when configured),
     * stores the conversion on the media's configured disk under the conversions path, and updates the media's
     * `conversions` attribute with the public URLs of the generated files before saving the model.
     *
     * @param  Model  $media  Media model whose `presets`, `disk`, `path`, and `file_name` identify the source file and where conversions should be stored.
     */
    public function generateConversions(Model $media): void
    {
        $conversions = [];
        foreach (json_decode($media->presets) as $preset) {

            // Load preset config
            $presetProps = json_decode($this->settingService->getSystemSettingValue(Setting::tryFrom($preset)));
            $conversionsPath = $this->settingService->getSystemSettingValue(Setting::MEDIA_CONVERSIONS_PATH);

            $fileName = File::name($media->file_name);
            $fileExtension = '.'.File::extension($media->file_name);

            $fullMediaPath = $media->fullMediaPath();
            $fullMediaDirectory = Storage::disk($media->disk)->path($media->path);

            $destPath = $fullMediaDirectory.'/'.$conversionsPath;

            if (! is_dir($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }

            $destPath .= '/'.$fileName.$presetProps->suffix.$fileExtension;

            if (is_file($fullMediaPath)) {
                $image = Image::load($fullMediaPath);
                if (isset($presetProps->resize)) {
                    $image->resize($presetProps->resize->width, $presetProps->resize->height);
                }

                if (isset($presetProps->fit)) {
                    $image->fit(Fit::tryFrom($presetProps->fit->method), $presetProps->fit->width, $presetProps->fit->height);
                }

                if (isset($presetProps->crop)) {
                    $image->crop($presetProps->crop->width, $presetProps->crop->height, CropPosition::tryFrom($presetProps->crop->position));
                }

                if ($presetProps->optimize) {
                    $image->optimize();
                }

                $image->save($destPath);
            }

            $conversions[$preset] = Storage::disk($media->disk)->url($media->path.'/'.$conversionsPath.'/'.$fileName.$presetProps->suffix.$fileExtension);
        }

        $media->conversions = $conversions;
        $media->save();
    }

    /**
     * Create default custom properties for a media item based on its MIME type.
     *
     * @param  array  $metadata  Associative array containing at least:
     *                           - 'mime_type' (string): the media MIME type
     *                           - 'original_filename' (string): the uploaded file's original name
     * @return MediaCustomPropertiesDto For images, returns a DTO with `alt` and `title` set to the original filename; for other types, returns an empty DTO.
     */
    private function setDefaultCustomProperties(array $metadata): MediaCustomPropertiesDto
    {
        switch ($this->detectMediaType($metadata['mime_type'])) {
            case MediaType::IMAGE:
                return new MediaCustomPropertiesDto(
                    alt: $metadata['original_filename'],
                    title: $metadata['original_filename'],
                );

            default:
                return new MediaCustomPropertiesDto(
                    alt: null,
                    title: null,
                );

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

    /**
     * Build a media storage path using the configured base path and today's year/month/day segments.
     *
     * @return string The media path composed of the configured base media path followed by year, month, and day segments.
     */
    private function generateMediaPath(): string
    {
        $basePath = $this->settingService->getSystemSettingValue(Setting::MEDIA_BASE_PATH);

        return implode('/', [
            $basePath, date('Y'), date('m'), date('d'),
        ]);

    }

    /**
     * Apply sorting to the given query using sort criteria from the provided filters.
     *
     * Applies an orderBy on the query when `sortBy` is present in the QueryFiltersDto, using `sortOrder` as the direction.
     *
     * @param  Builder  $query  The query builder to modify.
     * @param  \Wave8\Factotum\Base\Dto\QueryFiltersDto  $queryFilters  Contains `sortBy` (column) and `sortOrder` (direction) used for ordering.
     */
    public function applySorting(Builder $query, QueryFiltersDto $queryFilters): void
    {
        if ($queryFilters->sortBy) {
            $query->orderBy($queryFilters->sortBy, $queryFilters->sortOrder->value);
        }
    }

    public function applyFilters(Builder $query, ?array $searchFilters): void
    {
        foreach ($searchFilters as $field => $value) {

            $operator = substr($value, 0, 1);
            if (in_array($operator, ['<', '>'])) {

                $value = substr($value, 1);
                $query = $query->where($field, $operator, $value);

            } else {
                $query = $query->where($field, 'LIKE', "%$value%");
            }
        }
    }

    public function getMediaNotConverted(): Collection
    {
        return Media::whereNotNull('presets')
            ->whereNull('conversions')
            ->where('media_type', MediaType::IMAGE->value)
            ->get();
    }
}
