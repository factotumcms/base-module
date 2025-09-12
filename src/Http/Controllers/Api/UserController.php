<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Wave8\Factotum\Base\Contracts\Services\UserServiceInterface;
use Wave8\Factotum\Base\Dto\UserDto;
use Wave8\Factotum\Base\Http\Requests\Api\User\CreateUserRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Resources\UserResource;

readonly class UserController
{
    public function __construct(
        private UserServiceInterface $userService,
    ) {}

    public function create(CreateUserRequest $request): JsonResponse
    {
        $user = $this->userService->create(
            UserDto::from($request->all())
        );

        return ApiResponse::createSuccessful(
            message: 'User created successfully',
            data: UserResource::from($user),
        );
    }

    public function read(int $id): JsonResponse
    {
        return ApiResponse::createSuccessful('Not implemented yet');
    }

    public function update(int $id, FormRequest $request): JsonResponse
    {
        return ApiResponse::createSuccessful('Not implemented yet');
    }

    public function delete(int $id): JsonResponse
    {
        return ApiResponse::createSuccessful('Not implemented yet');
    }

    public function all(): JsonResponse
    {
        $users = $this->userService->getAll();

        return ApiResponse::createSuccessful(
            message: 'Users retrieved successfully',
            data: $users
        );
    }
}
