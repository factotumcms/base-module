<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Services\PermissionServiceInterface;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Resources\PermissionResource;

final readonly class PermissionController
{
    public function __construct(
        private PermissionServiceInterface $permissionService,
    ) {}

    public function index(): ApiResponse
    {
        $permissions = $this->permissionService->getAll();

        return ApiResponse::make(
            data: $permissions->map(fn ($el) => PermissionResource::from($el)),
        );
    }

    public function show(int $id): ApiResponse
    {
        $permission = $this->permissionService->show(
            id: $id
        );

        return ApiResponse::make(
            data: PermissionResource::from($permission)
        );
    }
}
