<?php

namespace Wave8\Factotum\Base\Dtos\Api\Backoffice\Setting;

use Spatie\LaravelData\Data;

class UpdateSettingDto extends Data
{
    public function __construct(
        public ?string $value = null,
        public ?string $description = null,
    ) {}

    public static function make(
        ?string $value = null,
        ?string $description = null,
    ): static {
        return new static(
            value: $value,
            description: $description,
        );
    }
}
