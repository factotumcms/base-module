<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Wave8\Factotum\Base\Contracts\Services\SettingServiceInterface;
use Wave8\Factotum\Base\Dto\Media\MediaPresetConfigDto;
use Wave8\Factotum\Base\Dto\Setting\CreateSettingDto;
use Wave8\Factotum\Base\Enum\Disk;
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
                data_type: SettingDataType::JSON,
                group: SettingGroup::MEDIA,
                key: Setting::PROFILE_PICTURE_PRESET,
                value: json_encode(MediaPresetConfigDto::make(
                    width: 200, height: 200, fit: 'crop', position: 'center'
                )),
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::JSON,
                group: SettingGroup::MEDIA,
                key: Setting::THUMBNAIL_PRESET,
                value: json_encode(MediaPresetConfigDto::make(
                    width: 200, height: 200, fit: 'crop', position: 'center'
                )),
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::STRING,
                group: SettingGroup::MEDIA,
                key: Setting::DEFAULT_MEDIA_DISK,
                value: Disk::PUBLIC->value,
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::STRING,
                group: SettingGroup::MEDIA,
                key: Setting::MEDIA_BASE_PATH,
                value: 'media',
            )
        );
    }
}
