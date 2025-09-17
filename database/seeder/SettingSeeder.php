<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Wave8\Factotum\Base\Contracts\Services\SettingServiceInterface;
use Wave8\Factotum\Base\Dto\Setting\CreateSettingDto;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Types\SettingDataType;
use Wave8\Factotum\Base\Types\SettingGroupType;
use Wave8\Factotum\Base\Types\SettingType;
use Wave8\Factotum\Base\Types\SettingTypeType;

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
                type: SettingTypeType::SYSTEM,
                data_type: SettingDataType::STRING,
                group: SettingGroupType::AUTH,
                key: SettingType::AUTH_TYPE,
                value: 'basic',
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                type: SettingTypeType::SYSTEM,
                data_type: SettingDataType::STRING,
                group: SettingGroupType::AUTH,
                key: SettingType::AUTH_BASIC_IDENTIFIER,
                value: 'email',
            )
        );
    }
}
