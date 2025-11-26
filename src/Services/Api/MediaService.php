<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Image\Enums\CropPosition;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Exceptions\CouldNotLoadImage;
use Spatie\Image\Image;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\MediaServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Media\CreateMediaDto;
use Wave8\Factotum\Base\Dtos\Api\Media\MediaCustomPropertiesDto;
use Wave8\Factotum\Base\Dtos\Api\Media\StoreFileDto;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\Media\MediaAction;
use Wave8\Factotum\Base\Enums\Media\MediaType;
use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Jobs\GenerateImagesConversions;
use Wave8\Factotum\Base\Models\Media;

use function Illuminate\Filesystem\join_paths;

class MediaService implements MediaServiceInterface
{
    public function __construct(
        /** @var SettingService $settingService */
        private readonly SettingServiceInterface $settingService,
        public readonly Media $media,
    ) {}

    public function create(Data $data): Model
    {
        return $this->media::create($data->toArray());
    }

    public function read(int $id): Model
    {
        return $this->media::findOrFail($id);
    }

    public function update(int $id, Data $data): Model
    {
        $media = $this->media::findOrFail($id);
        $media->update($data->toArray());

        return $media;
    }

    public function delete(int $id): void
    {
        // todo:: implement delete media logic
    }

    public function filter(): LengthAwarePaginator
    {
        $query = $this->media->query()
            ->filterByRequest();

        return $query->paginate();
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
        $disk = Disk::from($this->settingService->getValue(Setting::DEFAULT_MEDIA_DISK, SettingGroup::MEDIA));

        $i = 0;
        $suffix = '';
        do {
            if ($i > 0) {
                $suffix = '-'.$i;
            }
            $i++;

            $metadata['filename'] = "{$metadata['basename']}{$suffix}.{$metadata['extension']}";
        } while ($this->checkMediaUnique($metadata['filename'], $disk->value, $mediaBasePath));

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
                presets: array_keys($presetConfigs),
                disk: $disk,
                path: $mediaBasePath,
                size: $metadata['size'],
                customProperties: $this->setDefaultCustomProperties($metadata)
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
        return [
            'original_filename' => $filename = $file->getClientOriginalName(),
            'extension' => $extension = $file->getClientOriginalExtension(),
            'basename' => $basename = Str::slug(File::name($filename)),
            'filename' => "{$basename}.{$extension}",
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ];
    }

    /**
     * Checks if a file with the same name already exists in the database.
     * Throws an exception if a conflict is found to prevent overwriting existing files.
     *
     * @throws \Exception
     */
    private function checkMediaUnique(string $filename, string $disk, string $path): ?Model
    {
        $media = Media::where('file_name', $filename)
            ->where('disk', $disk)
            ->where('path', $path)
            ->first();

        return $media;
    }

    /**
     * Generate and save image conversions for each preset declared on the given media and persist their public URLs.
     *
     * For each preset listed in the media's `presets` JSON, this method loads the preset configuration from system
     * settings, produces a converted image (applying resize, fit, crop, and optional optimization when configured),
     * stores the conversion on the media's configured disk under the conversions path, and updates the media's
     * `conversions` attribute with the public URLs of the generated files before saving the model.
     *
     * @param  Media  $media  Media model whose `presets`, `disk`, `path`, and `file_name` identify the source file and where conversions should be stored.
     *
     * @throws CouldNotLoadImage
     */
    public function generateConversions(Media $media): void
    {
        $conversions = [];

        foreach ($media->presets as $preset) {
            // Load preset config
            $presetProps = $this->settingService->getValue(Setting::from($preset), SettingGroup::MEDIA);
            $conversionsPath = $this->settingService->getValue(Setting::MEDIA_CONVERSIONS_PATH, SettingGroup::MEDIA);

            $fileName = File::name($media->file_name);
            $fileExtension = '.'.File::extension($media->file_name);

            $fullMediaPath = $media->fullMediaPath();
            $fullMediaDirectory = Storage::disk($media->disk)->path($media->path);

            $destPath = join_paths($fullMediaDirectory, $conversionsPath);
            File::ensureDirectoryExists($destPath);

            $destPath = join_paths($destPath, $fileName.$presetProps['suffix'].$fileExtension);

            if (is_file($fullMediaPath)) {
                $image = Image::load($fullMediaPath);

                foreach ($presetProps['actions'] as $action => $actionConfigs) {
                    match ($action) {
                        MediaAction::RESIZE->value => $image = $this->applyResize($image, $actionConfigs),
                        MediaAction::FIT->value => $image = $this->applyFit($image, $actionConfigs),
                        MediaAction::CROP->value => $image = $this->applyCrop($image, $actionConfigs),
                        MediaAction::OPTIMIZE->value => $image = $this->applyOptimize($image),
                        default => throw new \Exception('Unsupported image action: '.$action),
                    };
                }

                $image->save($destPath);
            }

            $conversions[$preset] = Storage::disk($media->disk)->url($media->path.'/'.$conversionsPath.'/'.$fileName.$presetProps['suffix'].$fileExtension);
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
     *
     * @throws \Exception
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
            $config = $this->settingService->getValue(Setting::from($preset->value), SettingGroup::MEDIA);
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
     *
     * @throws \Exception
     */
    private function generateMediaPath(): string
    {
        $basePath = $this->settingService->getValue(Setting::MEDIA_BASE_PATH, SettingGroup::MEDIA);

        return implode('/', [
            $basePath, date('Y'), date('m'), date('d'),
        ]);
    }

    public function getMediaNotConverted(): Collection
    {
        return Media::whereNotNull('presets')
            ->whereNull('conversions')
            ->where('media_type', MediaType::IMAGE->value)
            ->get();
    }

    private function applyResize(Image $image, array $configs)
    {
        return $image->resize($configs['width'], $configs['height']);
    }

    private function applyFit(Image $image, array $configs)
    {
        return $image->fit(Fit::from($configs['method']), $configs['width'], $configs['height']);
    }

    private function applyCrop(Image $image, array $configs)
    {
        return $image->crop($configs['width'], $configs['height'], CropPosition::from($configs['position']));
    }

    private function applyOptimize(Image $image)
    {
        return $image->optimize();
    }
}
