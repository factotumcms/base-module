<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Wave8\Factotum\Base\Contracts\Services\UserServiceInterface;
use Wave8\Factotum\Base\Dto\User\CreateUserDto;
use Wave8\Factotum\Base\Dto\User\UpdateUserDto;
use Wave8\Factotum\Base\Http\Requests\Api\User\CreateUserRequest;
use Wave8\Factotum\Base\Http\Requests\Api\User\UpdateUserRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Resources\UserResource;

readonly class UserController
{
    public function __construct(
        private UserServiceInterface $userService,
    ) {}

    public function index(): JsonResponse
    {
        $users = $this->userService->getAll();

        return ApiResponse::createSuccessful(
            message: 'Users retrieved successfully',
            data: $users->map(fn ($el) => UserResource::from($el))
        );
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        $user = $this->userService->create(
            data: CreateUserDto::from($request->all())
        );

        return ApiResponse::createSuccessful(
            message: 'User created successfully',
            data: UserResource::from($user),
        );
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userService->show(
            id: $id
        );

        return ApiResponse::createSuccessful(
            message: '',
            data: UserResource::from($user)
        );
    }

    public function update(int $id, UpdateUserRequest $request): JsonResponse
    {
        $user = $this->userService->update(
            id: $id,
            data: UpdateUserDto::from($request->all())
        );

        return ApiResponse::createSuccessful(
            message: '',
            data: UserResource::from($user)
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $this->userService->delete($id);

        return ApiResponse::createSuccessful(
            message: 'ok'
        );
    }
}
