<?php

use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Enums\Setting\SettingDataType;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Enums\Setting\SettingVisibility;

describe('SettingEnums', function () {
    it('check Setting has exact values', function () {
        $values = array_flip(Setting::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'auth_type',
            'auth_basic_identifier',
            'user_avatar_preset',
            'thumbnail_preset',
            'resize_quality',
            'default_media_disk',
            'media_base_path',
            'media_conversions_path',
            'locale',
            'locale_available',
        ]);
    });

    it('check SettingDataType has exact values', function () {
        $values = array_flip(SettingDataType::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'integer',
            'float',
            'string',
            'boolean',
            'json',
        ]);
    });

    it('check SettingGroup has exact values', function () {
        $values = array_flip(SettingGroup::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'media',
            'auth',
            'locale',
            'pagination',
        ]);
    });

    it('check SettingScope has exact values', function () {
        $values = array_flip(SettingVisibility::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'system',
            'user',
        ]);
    });
});
