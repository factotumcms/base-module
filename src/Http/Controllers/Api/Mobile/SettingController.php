<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api\Mobile;

use Wave8\Factotum\Base\Contracts\Api\Mobile\SettingServiceInterface;
use Wave8\Factotum\Base\Http\Responses\Api\Mobile\ApiResponse;
use Wave8\Factotum\Base\Resources\Api\Mobile\SettingResource;

final readonly class SettingController
{
    public function __construct(
        private SettingServiceInterface $settingService,
    ) {}

    public function index(): ApiResponse
    {
        $settings = $this->settingService->getAll();

        return ApiResponse::make(
            data: $settings->map(fn ($el) => SettingResource::from($el)),
        );
    }

    public function show(int $id): ApiResponse
    {
        $setting = $this->settingService->show(
            id: $id
        );

        return ApiResponse::make(
            data: SettingResource::from($setting),
        );
    }
}
