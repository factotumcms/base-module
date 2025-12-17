<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Media\MediaPresetConfigDto;
use Wave8\Factotum\Base\Dtos\Api\Setting\CreateSettingDto;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\Locale;
use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Enums\Setting\SettingDataType;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;

class SettingSeeder extends Seeder
{
    public function __construct(
        private readonly SettingServiceInterface $settingService
    ) {}

    /**
     * Seed default system settings into the database.
     *
     * Creates default settings for authentication, locale, media, and pagination,
     * and registers media presets defined in the application configuration.
     */
    public function run(): void
    {
        // Create admin default user
        Log::info('Creating default system settings..');

        $this->settingService->create(
            data: new CreateSettingDto(
                isEditable: false,
                dataType: SettingDataType::STRING,
                group: SettingGroup::AUTH,
                key: Setting::AUTH_TYPE,
                value: 'basic',
                description: 'Authentication type (e.g., basic, microsoft)'
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                isEditable: false,
                dataType: SettingDataType::STRING,
                group: SettingGroup::AUTH,
                key: Setting::AUTH_BASIC_IDENTIFIER,
                value: 'email',
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                isEditable: false,
                dataType: SettingDataType::INTEGER,
                group: SettingGroup::AUTH,
                key: Setting::AUTH_TOKEN_EXPIRATION_DAYS,
                value: 5,
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                isEditable: false,
                dataType: SettingDataType::STRING,
                group: SettingGroup::LOCALE,
                key: Setting::LOCALE,
                value: Locale::tryFrom(config('factotum_base.locale.default'))->value,
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                isEditable: false,
                dataType: SettingDataType::JSON,
                group: SettingGroup::LOCALE,
                key: Setting::AVAILABLE_LOCALES,
                value: json_encode(Locale::getValues()),
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                isEditable: false,
                dataType: SettingDataType::STRING,
                group: SettingGroup::MEDIA,
                key: Setting::MEDIA_CONVERSIONS_PATH,
                value: config('factotum_base.media.conversions_path'),
            )
        );

        // Media presets
        foreach (config('factotum_base.media.presets') as $key => $preset) {
            $settingKey = Setting::tryFrom($key);
            if (! $settingKey) {
                continue; // Skip presets that don't map to Setting enum
            }

            $this->settingService->create(
                data: new CreateSettingDto(
                    isEditable: false,
                    dataType: SettingDataType::JSON,
                    group: SettingGroup::MEDIA,
                    key: $settingKey,
                    value: json_encode(new MediaPresetConfigDto(
                        suffix: $preset['suffix'],
                        actions: $preset['actions'] ?? [],
                    )),
                )
            );
        }

        $this->settingService->create(
            data: new CreateSettingDto(
                isEditable: false,
                dataType: SettingDataType::STRING,
                group: SettingGroup::MEDIA,
                key: Setting::DEFAULT_MEDIA_DISK,
                value: Disk::tryFrom(config('factotum_base.media.disk'))->value
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                isEditable: false,
                dataType: SettingDataType::STRING,
                group: SettingGroup::MEDIA,
                key: Setting::MEDIA_BASE_PATH,
                value: config('factotum_base.media.base_path')
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                isEditable: false,
                dataType: SettingDataType::JSON,
                group: SettingGroup::LOCALE,
                key: Setting::PUBLIC_LANGUAGE_GROUPS,
                value: json_encode([]),
                description: 'Language groups available to public users'
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                isEditable: true,
                dataType: SettingDataType::BOOLEAN,
                group: SettingGroup::NOTIFICATIONS,
                key: Setting::ENABLE_USER_VERIFY_EMAIL,
                value: 'true',
                description: 'Enable send user verification email to new users'
            )
        );
    }
}
