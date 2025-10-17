<?php

namespace Wave8\Factotum\Base\Dtos\Api\Setting;

use Spatie\LaravelData\Data;

class UpdateSettingDto extends Data
{
    public function __construct(
        public ?string $value = null,
        public ?string $description = null,
    ) {}
}
