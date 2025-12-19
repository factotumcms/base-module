<?php

use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Enums\Setting\SettingDataType;

describe('Setting model', function () {
    it('checks setting are defined', function () {
        $settingService = app(SettingServiceInterface::class);
        $settings = $settingService->getAll();

        expect($settings->isNotEmpty())->toBeTrue();

        $settings->each(function ($setting) {
            expect($setting->key)->not->toBeEmpty()
                ->and($setting->group)->not->toBeEmpty()
                ->and($setting->data_type)->not->toBeEmpty();
        });

        $settings->where('data_type', SettingDataType::STRING)->each(function ($setting) {
            expect(is_string($setting->value))->toBeTrue();
        });

        $settings->where('data_type', SettingDataType::INTEGER)->each(function ($setting) {
            expect(is_int($setting->value))->toBeTrue();
        });

        $settings->where('data_type', SettingDataType::FLOAT)->each(function ($setting) {
            expect(is_float($setting->value))->toBeTrue();
        });

        $settings->where('data_type', SettingDataType::BOOLEAN)->each(function ($setting) {
            expect(is_bool($setting->value))->toBeTrue();
        });

        $settings->where('data_type', SettingDataType::JSON)->each(function ($setting) {
            expect(is_array($setting->value) || is_object($setting->value))->toBeTrue();
        });
    });
});
