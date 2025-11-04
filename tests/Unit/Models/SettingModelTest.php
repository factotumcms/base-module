<?php

use Wave8\Factotum\Base\Models\Setting;

describe('SettingModel', function () {
    it('successfully creates a new Setting model instance', function () {
        $setting = new Setting;

        expect($setting)->toBeInstanceOf(Setting::class);
    });

    it('checks fillable columns', function () {
        $setting = new Setting;

        expect($setting->getFillable())->toEqual([
            'visibility',
            'data_type',
            'group',
            'key',
            'value',
            'description',
        ]);
    });
});
