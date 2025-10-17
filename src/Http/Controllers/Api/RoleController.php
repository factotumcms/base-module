<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Api\RoleServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Role\CreateRoleDto;
use Wave8\Factotum\Base\Dtos\Api\Role\UpdateRoleDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Http\Requests\Api\QueryFiltersRequest;
use Wave8\Factotum\Base\Http\Requests\Api\Role\CreateRoleRequest;
use Wave8\Factotum\Base\Http\Requests\Api\Role\UpdateRoleRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Resources\Api\RoleResource;

final readonly class RoleController
{
    public function __construct(
        private RoleServiceInterface $roleService,
    ) {}

    public function index(QueryFiltersRequest $request): ApiResponse
    {
        $roles = $this->roleService->filter(
            QueryFiltersDto::from($request)
        );

        return ApiResponse::make(
            data: RoleResource::collect($roles),
        );
    }

    public function store(CreateRoleRequest $request): ApiResponse
    {
        $role = $this->roleService->create(
            data: CreateRoleDto::from($request->all())
        );

        return ApiResponse::make(
            data: RoleResource::from($role)
        );
    }

    public function show(int $id): ApiResponse
    {
        $role = $this->roleService->show(
            id: $id
        );

        return ApiResponse::make(
            data: RoleResource::from($role)
        );
    }

    public function update(int $id, UpdateRoleRequest $request): ApiResponse
    {
        $role = $this->roleService->update(
            id: $id,
            data: UpdateRoleDto::from($request->all())
        );

        return ApiResponse::make(
            data: RoleResource::from($role)
        );
    }

    public function destroy(int $id): ApiResponse
    {
        $this->roleService->delete($id);

        return ApiResponse::make(
            data: 'ok'
        );
    }
}
