<?php

namespace Wave8\Factotum\Base\Dto;

use Spatie\LaravelData\Data;

class SettingDto extends Data
{
    public function __construct(
        public string $type,
        public string $data_type,
        public string $group,
        public string $key,
        public ?string $value = null,
    ) {}

}
