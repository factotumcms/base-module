<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Support\Facades\Gate;
use Wave8\Factotum\Base\Contracts\Services\SettingServiceInterface;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Models\Setting;
use Wave8\Factotum\Base\Resources\SettingResource;

final readonly class SettingController
{
    public function __construct(
        private SettingServiceInterface $settingService,
    ) {}

    public function index(): ApiResponse
    {
        Gate::authorize('read', Setting::class);
        $settings = $this->settingService->getAll();

        return ApiResponse::make(
            data: $settings->map(fn ($el) => SettingResource::from($el)),
        );
    }

    public function show(int $id): ApiResponse
    {
        Gate::authorize('read', Setting::class);
        $setting = $this->settingService->show(
            id: $id
        );

        return ApiResponse::make(
            data: SettingResource::from($setting),
        );
    }
}
