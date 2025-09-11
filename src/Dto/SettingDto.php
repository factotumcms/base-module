<?php

namespace Wave8\Factotum\Base\Dto;

use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Types\SettingDataType;
use Wave8\Factotum\Base\Types\SettingGroupType;
use Wave8\Factotum\Base\Types\SettingType;
use Wave8\Factotum\Base\Types\SettingTypeType;

class SettingDto extends Data
{
    public function __construct(
        public SettingTypeType $type,
        public SettingDataType $data_type,
        public SettingGroupType $group,
        public SettingType $key,
        public ?string $value = null,
    ) {}

}
