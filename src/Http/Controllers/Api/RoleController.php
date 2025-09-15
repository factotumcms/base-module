<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Wave8\Factotum\Base\Contracts\Services\RoleServiceInterface;
use Wave8\Factotum\Base\Dto\User\CreateUserDto;
use Wave8\Factotum\Base\Dto\User\UpdateUserDto;
use Wave8\Factotum\Base\Http\Requests\Api\User\CreateUserRequest;
use Wave8\Factotum\Base\Http\Requests\Api\User\UpdateUserRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Resources\UserResource;

final readonly class RoleController
{
    public function __construct(
        private RoleServiceInterface $roleService,
    ) {}

    public function index(): ApiResponse
    {
        $users = $this->roleService->getAll();

        return ApiResponse::make(
            data: $users->map(fn ($el) => UserResource::from($el)),
        );
    }

    public function store(CreateUserRequest $request): ApiResponse
    {
        $user = $this->roleService->create(
            data: CreateUserDto::from($request->all())
        );

        return ApiResponse::make(
            data: UserResource::from($user)
        );
    }

    public function show(int $id): ApiResponse
    {
        $user = $this->roleService->show(
            id: $id
        );

        return ApiResponse::make(
            data: UserResource::from($user)
        );
    }

    public function update(int $id, UpdateUserRequest $request): ApiResponse
    {
        $user = $this->roleService->update(
            id: $id,
            data: UpdateUserDto::from($request->all())
        );

        return ApiResponse::make(
            data: UserResource::from($user)
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
