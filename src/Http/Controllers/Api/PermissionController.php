<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Api\PermissionServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\QueryPaginationDto;
use Wave8\Factotum\Base\Http\Requests\Api\QueryFiltersRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Models\Permission;
use Wave8\Factotum\Base\Resources\Api\PermissionResource;
use Wave8\Factotum\Base\Services\Api\PermissionService;

final readonly class PermissionController
{
    public string $permissionResource;

    public function __construct(
        /** @var $permissionService PermissionService */
        private PermissionServiceInterface $permissionService,
    ) {
        $this->permissionResource = config('data_transfer.'.PermissionResource::class);
    }

    public function index(QueryFiltersRequest $request): ApiResponse
    {
        $permissions = $this->permissionService
            ->filter(
                paginationDto: QueryPaginationDto::from($request),
            );

        return ApiResponse::make(
            data: $this->permissionResource::collect($permissions)
        );
    }

    public function show(Permission $permission): ApiResponse
    {
        return ApiResponse::make(
            data: $this->permissionResource::from($permission)
        );
    }
}
