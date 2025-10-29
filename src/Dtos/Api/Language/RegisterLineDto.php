<?php

namespace Wave8\Factotum\Base\Dtos\Api\Language;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Wave8\Factotum\Base\Enums\Locale;

#[MapName(SnakeCaseMapper::class)]
class RegisterLineDto extends Data
{
    public function __construct(
        public readonly Locale $locale,
        public readonly string $group,
        public readonly string $key,
        public readonly string $line,
    ) {}
}
