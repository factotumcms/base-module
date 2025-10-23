<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Media\MediaCropDto;
use Wave8\Factotum\Base\Dtos\Api\Media\MediaFitDto;
use Wave8\Factotum\Base\Dtos\Api\Media\MediaPresetConfigDto;
use Wave8\Factotum\Base\Dtos\Api\Media\MediaResizeDto;
use Wave8\Factotum\Base\Dtos\Api\Setting\CreateSettingDto;
use Wave8\Factotum\Base\Enums\Disk;
use Wave8\Factotum\Base\Enums\Locale;
use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Enums\Setting\SettingDataType;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Enums\Setting\SettingScope;

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
                scope: SettingScope::SYSTEM,
                dataType: SettingDataType::STRING,
                group: SettingGroup::AUTH,
                key: Setting::AUTH_TYPE,
                value: 'basic',
                description: 'Authentication type (e.g., basic, microsoft)'
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                scope: SettingScope::SYSTEM,
                dataType: SettingDataType::STRING,
                group: SettingGroup::AUTH,
                key: Setting::AUTH_BASIC_IDENTIFIER,
                value: 'email',
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                scope: SettingScope::SYSTEM,
                dataType: SettingDataType::STRING,
                group: SettingGroup::LOCALE,
                key: Setting::LOCALE_DEFAULT,
                value: Locale::tryFrom(config('factotum-base.locale.default'))->value,
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                scope: SettingScope::SYSTEM,
                dataType: SettingDataType::JSON,
                group: SettingGroup::LOCALE,
                key: Setting::LOCALE_AVAILABLE,
                value: json_encode(Locale::getValues()),
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                scope: SettingScope::SYSTEM,
                dataType: SettingDataType::STRING,
                group: SettingGroup::MEDIA,
                key: Setting::MEDIA_CONVERSIONS_PATH,
                value: config('factotum-base.media.conversions_path'),
            )
        );

        // Media presets
        foreach (config('factotum-base.media.presets') as $key => $preset) {
            $settingKey = Setting::tryFrom($key);
            if (! $settingKey) {
                continue; // Skip presets that don't map to Setting enum
            }

            $this->settingService->create(
                data: new CreateSettingDto(
                    scope: SettingScope::SYSTEM,
                    dataType: SettingDataType::JSON,
                    group: SettingGroup::MEDIA,
                    key: $settingKey,
                    value: json_encode(new MediaPresetConfigDto(
                        suffix: $preset['suffix'],
                        optimize: $preset['optimize'] ?? true,
                        resize: isset($preset['resize']) ? MediaResizeDto::from($preset['resize']) : null,
                        fit: isset($preset['fit']) ? MediaFitDto::from($preset['fit']) : null,
                        crop: isset($preset['crop']) ? MediaCropDto::from($preset['crop']) : null,
                    )),
                )
            );
        }

        $this->settingService->create(
            data: new CreateSettingDto(
                scope: SettingScope::SYSTEM,
                dataType: SettingDataType::STRING,
                group: SettingGroup::MEDIA,
                key: Setting::DEFAULT_MEDIA_DISK,
                value: Disk::tryFrom(config('factotum-base.media.disk'))->value
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                scope: SettingScope::SYSTEM,
                dataType: SettingDataType::STRING,
                group: SettingGroup::MEDIA,
                key: Setting::MEDIA_BASE_PATH,
                value: config('factotum-base.media.base_path')
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                scope: SettingScope::SYSTEM,
                dataType: SettingDataType::INTEGER,
                group: SettingGroup::PAGINATION,
                key: Setting::PAGINATION_PER_PAGE,
                value: config('factotum-base.pagination.per_page')
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                scope: SettingScope::SYSTEM,
                dataType: SettingDataType::INTEGER,
                group: SettingGroup::PAGINATION,
                key: Setting::PAGINATION_DEFAULT_ORDER_BY,
                value: config('factotum-base.pagination.default_order_by')
            )
        );

        $this->settingService->create(
            data: new CreateSettingDto(
                scope: SettingScope::SYSTEM,
                dataType: SettingDataType::INTEGER,
                group: SettingGroup::PAGINATION,
                key: Setting::PAGINATION_DEFAULT_ORDER_DIRECTION,
                value: config('factotum-base.pagination.default_order_direction')
            )
        );
    }
}
