<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Services\UserServiceInterface;
use Wave8\Factotum\Base\Dtos\User\CreateUserDto;
use Wave8\Factotum\Base\Dtos\User\UpdateUserDto;
use Wave8\Factotum\Base\Http\Requests\Api\User\CreateUserRequest;
use Wave8\Factotum\Base\Http\Requests\Api\User\UpdateUserRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Resources\UserResource;

final readonly class UserController
{
    public function __construct(
        private UserServiceInterface $userService,
    ) {}

    public function index(): ApiResponse
    {
        $users = $this->userService->getAll();

        return ApiResponse::make(
            data: $users->map(fn ($el) => UserResource::from($el)),
        );
    }

    public function store(CreateUserRequest $request): ApiResponse
    {
        $user = $this->userService->create(
            data: CreateUserDto::from($request->all())
        );

        return ApiResponse::make(
            data: UserResource::from($user)
        );
    }

    public function show(int $id): ApiResponse
    {
        $user = $this->userService->show(
            id: $id
        );

        return ApiResponse::make(
            data: UserResource::from($user)
        );
    }

    public function update(int $id, UpdateUserRequest $request): ApiResponse
    {
        $user = $this->userService->update(
            id: $id,
            data: UpdateUserDto::from($request->all())
        );

        return ApiResponse::make(
            data: UserResource::from($user)
        );
    }

    public function destroy(int $id): ApiResponse
    {
        $this->userService->delete($id);

        return ApiResponse::make(
            data: 'ok'
        );
    }
}
