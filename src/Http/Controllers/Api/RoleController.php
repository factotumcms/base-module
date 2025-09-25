<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Support\Facades\Gate;
use Wave8\Factotum\Base\Contracts\Services\RoleServiceInterface;
use Wave8\Factotum\Base\Dtos\Role\CreateRoleDto;
use Wave8\Factotum\Base\Dtos\Role\UpdateRoleDto;
use Wave8\Factotum\Base\Http\Requests\Api\Role\CreateRoleRequest;
use Wave8\Factotum\Base\Http\Requests\Api\Role\UpdateRoleRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Models\Role;
use Wave8\Factotum\Base\Resources\RoleResource;

final readonly class RoleController
{
    public function __construct(
        private RoleServiceInterface $roleService,
    ) {}

    public function index(): ApiResponse
    {
        $roles = $this->roleService->getAll();

        return ApiResponse::make(
            data: $roles->map(fn ($el) => RoleResource::from($el)),
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
        Gate::authorize('delete', Role::class);
        $this->roleService->delete($id);

        return ApiResponse::make(
            data: 'ok'
        );
    }
}
