<?php
describe('SettingEnums', function () {
    it('check Setting has exact values', function () {

        $values = array_flip(\Wave8\Factotum\Base\Enums\Setting\Setting::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'auth_type',
            'auth_basic_identifier',
            'profile_picture_preset',
            'thumbnail_preset',
            'resize_quality',
            'default_media_disk',
            'media_base_path',
            'media_conversions_path',
            'locale_default',
            'locale_available',
            'pagination_per_page',
            'pagination_default_order_by',
            'pagination_default_order_direction',
        ]);

    });

    it('check SettingDataType has exact values', function () {

        $values = array_flip(\Wave8\Factotum\Base\Enums\Setting\SettingDataType::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'integer',
            'float',
            'string',
            'boolean',
            'json',
        ]);

    });

    it('check SettingGroup has exact values', function () {

        $values = array_flip(\Wave8\Factotum\Base\Enums\Setting\SettingGroup::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'media',
            'auth',
            'locale',
            'pagination',
        ]);

    });

    it('check SettingScope has exact values', function () {

        $values = array_flip(\Wave8\Factotum\Base\Enums\Setting\SettingScope::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'system',
            'user',
        ]);

    });
});
