<?php

namespace Wave8\Factotum\Base\Dtos\Api\Setting;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Enums\Setting\SettingDataType;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Enums\Setting\SettingScope;

#[MapName(SnakeCaseMapper::class)]
class CreateSettingDto extends Data
{
    public function __construct(
        public SettingScope $scope,
        public SettingDataType $dataType,
        public SettingGroup $group,
        public Setting $key,
        public ?string $value = null,
        public ?string $description = null,
    ) {}
}
