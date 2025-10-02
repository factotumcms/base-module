<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api\Backoffice;

use Wave8\Factotum\Base\Contracts\Api\Backoffice\PermissionServiceInterface;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Http\Requests\Api\QueryFiltersRequest;
use Wave8\Factotum\Base\Http\Responses\Api\Backoffice\ApiResponse;
use Wave8\Factotum\Base\Resources\Api\Backoffice\PermissionResource;

final readonly class PermissionController
{
    public function __construct(
        private PermissionServiceInterface $permissionService,
    ) {}

    public function index(QueryFiltersRequest $request): ApiResponse
    {
        $permissions = $this->permissionService
            ->filter(
                QueryFiltersDto::make(
                    ...$request->query()
                )
            );

        return ApiResponse::make(
            data: PermissionResource::collect($permissions)
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
