<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\SettingServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\Media\MediaPresetConfigDto;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\Setting\CreateSettingDto;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\Locale;
use Wave8\Factotum\Base\Enums\Setting;
use Wave8\Factotum\Base\Enums\SettingDataType;
use Wave8\Factotum\Base\Enums\SettingGroup;
use Wave8\Factotum\Base\Enums\SettingScope;

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
                value: Locale::tryFrom(config('factotum-base.locale.default'))->value,
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
                    width: config('factotum-base.media.profile_picture_preset.width'),
                    height: config('factotum-base.media.profile_picture_preset.height'),
                    fit: config('factotum-base.media.profile_picture_preset.fit'),
                    position: config('factotum-base.media.profile_picture_preset.position'),
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
                    width: config('factotum-base.media.thumbnail_preset.width'),
                    height: config('factotum-base.media.thumbnail_preset.height'),
                    fit: config('factotum-base.media.thumbnail_preset.fit'),
                    position: config('factotum-base.media.thumbnail_preset.position'),
                )),
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::STRING,
                group: SettingGroup::MEDIA,
                key: Setting::DEFAULT_MEDIA_DISK,
                value: Disk::tryFrom(config('factotum-base.media.disk'))->value
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::STRING,
                group: SettingGroup::MEDIA,
                key: Setting::MEDIA_BASE_PATH,
                value: config('factotum-base.media.base_path')
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::INTEGER,
                group: SettingGroup::PAGINATION,
                key: Setting::PAGINATION_PER_PAGE,
                value: config('factotum-base.pagination.per_page')
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::INTEGER,
                group: SettingGroup::PAGINATION,
                key: Setting::PAGINATION_DEFAULT_ORDER_BY,
                value: config('factotum-base.pagination.default_order_by')
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                scope: SettingScope::SYSTEM,
                data_type: SettingDataType::INTEGER,
                group: SettingGroup::PAGINATION,
                key: Setting::PAGINATION_DEFAULT_ORDER_DIRECTION,
                value: config('factotum-base.pagination.default_order_direction')
            )
        );
    }
}
