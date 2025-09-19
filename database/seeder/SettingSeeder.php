<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Wave8\Factotum\Base\Contracts\Services\SettingServiceInterface;
use Wave8\Factotum\Base\Dto\Setting\CreateSettingDto;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Types\Locale;
use Wave8\Factotum\Base\Types\Setting;
use Wave8\Factotum\Base\Types\SettingDataType;
use Wave8\Factotum\Base\Types\SettingGroup;
use Wave8\Factotum\Base\Types\SettingScope;

class SettingSeeder extends Seeder
{
    public function __construct(
        private readonly SettingServiceInterface $settingService
    ) {}

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin default user
        Log::info('Creating default system settings..');

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::STRING,
                group: SettingGroup::AUTH,
                key: Setting::AUTH_TYPE,
                value: 'basic',
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::STRING,
                group: SettingGroup::AUTH,
                key: Setting::AUTH_BASIC_IDENTIFIER,
                value: 'email',
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::STRING,
                group: SettingGroup::LOCALE,
                key: Setting::LOCALE_DEFAULT,
                value: Locale::en_GB->value,
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::JSON,
                group: SettingGroup::LOCALE,
                key: Setting::LOCALE_AVAILABLE,
                value: json_encode(Locale::getValues()),
            )
        );
    }
}
