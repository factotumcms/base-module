<?php

use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Enums\Setting\SettingDataType;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;

describe('SettingEnums', function () {
    it('check Setting has exact values', function () {
        $values = array_flip(Setting::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'auth_type',
            'auth_basic_identifier',
            'auth_token_expiration_days',
            'user_avatar_preset',
            'thumbnail_preset',
            'default_media_disk',
            'media_base_path',
            'media_conversions_path',
            'locale',
            'available_locales',
            'public_groups',
            'enable_user_verify_email',
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
            'notifications',
        ]);
    });
});
