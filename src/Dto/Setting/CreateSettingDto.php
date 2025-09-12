<?php

namespace Wave8\Factotum\Base\Dto\Setting;

use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Types\SettingDataType;
use Wave8\Factotum\Base\Types\SettingGroupType;
use Wave8\Factotum\Base\Types\SettingType;
use Wave8\Factotum\Base\Types\SettingTypeType;

class CreateSettingDto extends Data
{
    public function __construct(
        public SettingTypeType $type,
        public SettingDataType $data_type,
        public SettingGroupType $group,
        public SettingType $key,
        public ?string $value = null,
    ) {}

    public static function make(
        SettingTypeType $type,
        SettingDataType $data_type,
        SettingGroupType $group,
        SettingType $key,
        ?string $value = null,
    ): static {
        return new static(
            type: $type,
            data_type: $data_type,
            group: $group,
            key: $key,
            value: $value,
        );
    }

}
