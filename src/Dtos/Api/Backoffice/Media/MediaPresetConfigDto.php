<?php

namespace Wave8\Factotum\Base\Dtos\Api\Backoffice\Media;

use Spatie\LaravelData\Data;

class MediaPresetConfigDto extends Data
{
    /**
     * Create a media preset configuration describing how a media variant should be produced.
     *
     * @param  string  $suffix  Suffix used to identify the generated variant (for example "-thumb").
     * @param  bool  $optimize  Whether to apply optimization to the generated media.
     * @param  MediaResizeDto|null  $resize  Optional resize settings; null if no resizing is requested.
     * @param  MediaFitDto|null  $fit  Optional fit settings; null if no fit behavior is requested.
     * @param  MediaCropDto|null  $crop  Optional crop settings; null if no cropping is requested.
     */
    public function __construct(
        public readonly string $suffix,
        public bool $optimize,
        public readonly ?MediaResizeDto $resize,
        public readonly ?MediaFitDto $fit,
        public readonly ?MediaCropDto $crop,
        // public readonly filter,
        // public readonly brightness,
        // public readonly contrast,
        // public readonly gamma,
        // public readonly orientation,
    ) {}

    /**
     * Create a MediaPresetConfigDto configured with a suffix and optional image transformation settings.
     *
     * @param  string  $suffix  Suffix or identifier appended to generated media (e.g., filename suffix or preset key).
     * @param  bool  $optimize  Whether the preset should be optimized (true) or left unoptimized (false).
     * @param  MediaResizeDto|null  $resize  Optional resize configuration or null to leave unset.
     * @param  MediaFitDto|null  $fit  Optional fit configuration or null to leave unset.
     * @param  MediaCropDto|null  $crop  Optional crop configuration or null to leave unset.
     * @return static The created DTO instance configured with the provided values.
     */
    public static function make(
        string $suffix,
        bool $optimize = true,
        ?MediaResizeDto $resize = null,
        ?MediaFitDto $fit = null,
        ?MediaCropDto $crop = null,
    ): static {
        return new static(
            suffix: $suffix,
            optimize: $optimize,
            resize: $resize,
            fit: $fit,
            crop: $crop,
        );
    }
}
