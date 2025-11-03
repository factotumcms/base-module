<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Api\RoleServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Role\CreateRoleDto;
use Wave8\Factotum\Base\Dtos\Api\Role\UpdateRoleDto;
use Wave8\Factotum\Base\Http\Requests\Api\Role\CreateRoleRequest;
use Wave8\Factotum\Base\Http\Requests\Api\Role\UpdateRoleRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Models\Role;
use Wave8\Factotum\Base\Resources\Api\RoleResource;
use Wave8\Factotum\Base\Services\Api\RoleService;

final readonly class RoleController
{
    private string $roleResource;

    public function __construct(
        /** @var $roleService RoleService */
        private RoleServiceInterface $roleService,
    ) {
        $this->roleResource = config('data_transfer.'.RoleResource::class);
    }

    public function index(): ApiResponse
    {
        $roles = $this->roleService->filter();

        return ApiResponse::make(
            data: $this->roleResource::collect($roles),
        );
    }

    public function store(CreateRoleRequest $request): ApiResponse
    {
        $createRoleDto = config('data_transfer.'.CreateRoleDto::class);

        $role = $this->roleService->create(
            data: $createRoleDto::from($request)
        );

        return ApiResponse::make(
            data: $this->roleResource::from($role)
        );
    }

    public function show(Role $role): ApiResponse
    {
        return ApiResponse::make(
            data: $this->roleResource::from($role)
        );
    }

    public function update(Role $role, UpdateRoleRequest $request): ApiResponse
    {
        $updateRoleDto = config('data_transfer.'.UpdateRoleDto::class);

        $role = $this->roleService->update(
            id: $role->id,
            data: $updateRoleDto::from($request)
        );

        return ApiResponse::make(
            data: $this->roleResource::from($role)
        );
    }

    public function destroy(Role $role): ApiResponse
    {
        $this->roleService->delete($role->id);

        return ApiResponse::HttpNoContent();
    }
}
