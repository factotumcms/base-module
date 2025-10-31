<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Models\Setting;
use Wave8\Factotum\Base\Resources\Api\SettingResource;
use Wave8\Factotum\Base\Services\Api\SettingService;

final readonly class SettingController
{
    private string $settingResource;

    public function __construct(
        /** @var $settingService SettingService */
        private SettingServiceInterface $settingService,
    ) {
        $this->settingResource = config('data_transfer.'.SettingResource::class);
    }

    public function index(): ApiResponse
    {
        $settings = $this->settingService->filter();

        return ApiResponse::make(
            data: $this->settingResource::collect($settings)
        );
    }

    public function show(Setting $setting): ApiResponse
    {
        return ApiResponse::make(
            data: $this->settingResource::from($setting),
        );
    }
}
