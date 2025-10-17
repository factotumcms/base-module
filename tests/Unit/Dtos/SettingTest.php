<?php

use Wave8\Factotum\Base\Dtos\Api\Setting\CreateSettingDto;
use Wave8\Factotum\Base\Dtos\Api\Setting\UpdateSettingDto;
use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Enums\Setting\SettingDataType;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Enums\Setting\SettingScope;

describe('SettingDto', function () {
    it('successfully creates a new CreateSettingDto instance', function () {

        $dto = new CreateSettingDto(
            scope: SettingScope::SYSTEM,
            dataType: SettingDataType::INTEGER,
            group: SettingGroup::AUTH,
            key: Setting::THUMBNAIL_PRESET,
            value: 'test', description: 'test'
        );

        expect($dto)->toBeInstanceOf(CreateSettingDto::class);
    });

    it('successfully creates a new UpdateSettingDto instance', function () {

        $dto = new UpdateSettingDto(
            value: 'test', description: 'web'
        );

        expect($dto)->toBeInstanceOf(UpdateSettingDto::class);
    });

});
