<?php

describe('SettingModel', function () {
    it('successfully creates a new Setting model instance', function () {
        $setting = new \Wave8\Factotum\Base\Models\Setting();

        expect($setting)->toBeInstanceOf(\Wave8\Factotum\Base\Models\Setting::class);
    });

    it('checks fillable columns', function () {

        $setting = new \Wave8\Factotum\Base\Models\Setting();

        expect($setting->getFillable())->toEqual([
            'scope',
            'data_type',
            'group',
            'key',
            'value',
            'description',
        ]);
    });
});



