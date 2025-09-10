<?php

namespace Wave8\Factotum\Base\Resources;

use Spatie\LaravelData\Resource;

class SettingResource extends Resource
{
    public function __construct(
        public string $data_type,
        public ?string $key,
        public ?string $value,
    ) {}
}
