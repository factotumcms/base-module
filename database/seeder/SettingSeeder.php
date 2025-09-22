<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Wave8\Factotum\Base\Contracts\Services\SettingServiceInterface;
use Wave8\Factotum\Base\Dto\Setting\CreateSettingDto;
use Wave8\Factotum\Base\Enum\Locale;
use Wave8\Factotum\Base\Enum\Setting;
use Wave8\Factotum\Base\Enum\SettingDataType;
use Wave8\Factotum\Base\Enum\SettingGroup;
use Wave8\Factotum\Base\Enum\SettingScope;
use Wave8\Factotum\Base\Models\User;

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

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::INTEGER,
                group: SettingGroup::MEDIA,
                key: Setting::THUMB_SIZE_WIDTH,
                value: 100,
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::INTEGER,
                group: SettingGroup::MEDIA,
                key: Setting::THUMB_SIZE_HEIGHT,
                value: 100,
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::STRING,
                group: SettingGroup::MEDIA,
                key: Setting::THUMB_PATH,
                value: 'thumbs',
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::STRING,
                group: SettingGroup::MEDIA,
                key: Setting::THUMB_SUFFIX,
                value: '_thumb',
            )
        );
    }
}
