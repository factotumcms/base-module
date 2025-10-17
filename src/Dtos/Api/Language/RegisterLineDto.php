<?php

namespace Wave8\Factotum\Base\Dtos\Api\Language;

use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Enums\Locale;

class RegisterLineDto extends Data
{
    public function __construct(
        public readonly Locale $locale,
        public readonly string $group,
        public readonly string $key,
        public readonly string $line,
    ) {}
}
