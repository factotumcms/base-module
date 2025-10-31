<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Api\UserServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\User\CreateUserDto;
use Wave8\Factotum\Base\Dtos\Api\User\UpdateUserDto;
use Wave8\Factotum\Base\Http\Requests\Api\User\CreateUserRequest;
use Wave8\Factotum\Base\Http\Requests\Api\User\UpdateUserRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Resources\Api\UserResource;
use Wave8\Factotum\Base\Services\Api\UserService;

final class UserController
{
    private string $userResource;

    final public function __construct(
        /** @var $userservice UserService */
        private UserServiceInterface $userService,
    ) {
        $this->userResource = config('data_transfer.'.UserResource::class);
    }

    final public function store(CreateUserRequest $request): ApiResponse
    {
        $createUserDto = config('data_transfer.'.CreateUserDto::class);

        $user = $this->userService->create(
            data: $createUserDto::from($request)
        );

        return ApiResponse::make(
            data: $this->userResource::from($user),
            status: ApiResponse::HTTP_CREATED
        );
    }

    final public function show(int $id): ApiResponse
    {
        $user = $this->userService->read(
            id: $id,
        );

        return ApiResponse::make(
            data: $this->userResource::from($user),
        );
    }

    final public function update(int $id, UpdateUserRequest $request): ApiResponse
    {
        $updateUserDto = config('data_transfer.'.UpdateUserDto::class);

        $user = $this->userService->update(
            id: $id,
            data: $updateUserDto::from($request)
        );

        return ApiResponse::make(
            data: $this->userResource::from($user),
        );
    }

    final public function destroy(int $id): ApiResponse
    {
        $this->userService->delete(
            id: $id,
        );

        return ApiResponse::HttpNoContent();
    }
}
