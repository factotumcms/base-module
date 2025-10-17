<?php

namespace Wave8\Factotum\Base\Dtos\Api\Media;

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
    ) {}
}
