<?php

namespace Wave8\Factotum\Base\Dtos\Setting;

use Spatie\LaravelData\Data;

class UpdateSettingDto extends Data
{
    public function __construct(
        public ?string $value = null,
    ) {}

    public static function make(
        ?string $value = null,
    ): static {
        return new static(
            value: $value,
        );
    }
}
