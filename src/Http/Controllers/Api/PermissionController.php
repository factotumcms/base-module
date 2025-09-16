<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Services\PermissionServiceInterface;
use Wave8\Factotum\Base\Dto\Permission\CreatePermissionDto;
use Wave8\Factotum\Base\Dto\Permission\UpdatePermissionDto;
use Wave8\Factotum\Base\Http\Requests\Api\Permission\CreatePermissionRequest;
use Wave8\Factotum\Base\Http\Requests\Api\Permission\UpdatePermissionRequest;
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

    public function store(CreatePermissionRequest $request): ApiResponse
    {
        $permission = $this->permissionService->create(
            data: CreatePermissionDto::from($request->all())
        );

        return ApiResponse::make(
            data: PermissionResource::from($permission)
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

    public function update(int $id, UpdatePermissionRequest $request): ApiResponse
    {
        $permission = $this->permissionService->update(
            id: $id,
            data: UpdatePermissionDto::from($request->all())
        );

        return ApiResponse::make(
            data: PermissionResource::from($permission)
        );
    }

    public function destroy(int $id): ApiResponse
    {
        $this->permissionService->delete($id);

        return ApiResponse::make(
            data: 'ok'
        );
    }
}
