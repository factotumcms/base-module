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
                value: Locale::tryFrom(config('factotum_base_config.locale.default'))->value,
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
                    width: config('factotum_base_config.media.profile_picture_preset.width'),
                    height: config('factotum_base_config.media.profile_picture_presets.height'),
                    fit: config('factotum_base_config.media.profile_picture_preset.fit'),
                    position: config('factotum_base_config.media.profile_picture_presets.position'),
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
                    width: config('factotum_base_config.media.thumbnail_preset.width'),
                    height: config('factotum_base_config.media.thumbnail_preset.height'),
                    fit: config('factotum_base_config.media.thumbnail_preset.fit'),
                    position: config('factotum_base_config.media.thumbnail_preset.position'),
                )),
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::STRING,
                group: SettingGroup::MEDIA,
                key: Setting::DEFAULT_MEDIA_DISK,
                value: Disk::tryFrom(config('factotum_base_config.media.disk'))->value
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::STRING,
                group: SettingGroup::MEDIA,
                key: Setting::MEDIA_BASE_PATH,
                value: config('factotum_base_config.media.base_path')
            )
        );
    }
}
