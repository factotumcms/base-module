<?php

namespace Wave8\Factotum\Base\Dtos\Setting;

use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Enum\Setting;
use Wave8\Factotum\Base\Enum\SettingDataType;
use Wave8\Factotum\Base\Enum\SettingGroup;
use Wave8\Factotum\Base\Enum\SettingScope;

class CreateSettingDto extends Data
{
    public function __construct(
        public SettingScope $scope,
        public SettingDataType $data_type,
        public SettingGroup $group,
        public Setting $key,
        public ?string $value = null,
    ) {}

    public static function make(
        SettingScope $scope,
        SettingDataType $data_type,
        SettingGroup $group,
        Setting $key,
        ?string $value = null,
    ): static {
        return new static(
            scope: $scope,
            data_type: $data_type,
            group: $group,
            key: $key,
            value: $value,
        );
    }
}
