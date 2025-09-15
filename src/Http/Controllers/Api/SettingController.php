<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Wave8\Factotum\Base\Contracts\Services\SettingServiceInterface;
use Wave8\Factotum\Base\Dto\Setting\UpdateSettingDto;
use Wave8\Factotum\Base\Http\Requests\Api\Setting\UpdateSettingRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Resources\SettingResource;

readonly class SettingController
{
    public function __construct(
        private SettingServiceInterface $settingService,
    ) {}

    public function index(): JsonResponse
    {
        $settings = $this->settingService->getAll();

        return ApiResponse::createSuccessful(
            message: 'Settings retrieved successfully',
            data: $settings->map(fn ($el) => SettingResource::from($el))
        );
    }

    public function store(Request $request): JsonResponse
    {
        return ApiResponse::createCustom(
            message: 'Not implemented',
            status: Response::HTTP_FORBIDDEN
        );
    }

    public function show(int $id): JsonResponse
    {
        $setting = $this->settingService->show(
            id: $id
        );

        return ApiResponse::createSuccessful(
            message: 'ok',
            data: SettingResource::from($setting)
        );
    }

    public function update(int $id, UpdateSettingRequest $request): JsonResponse
    {
        $setting = $this->settingService->update(
            id: $id,
            data: UpdateSettingDto::from($request->all())
        );

        return ApiResponse::createSuccessful(
            message: 'ok',
            data: SettingResource::from($setting)
        );
    }

    public function destroy(int $id): JsonResponse
    {
        return ApiResponse::createCustom(
            message: 'Not implemented',
            status: Response::HTTP_FORBIDDEN
        );

    }
}
