<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Spatie\LaravelData\Dto;
use Wave8\Factotum\Base\Contracts\Api\UserServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\User\CreateUserDto;
use Wave8\Factotum\Base\Http\Requests\Api\User\CreateUserRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;

final class UserController
{
    final public function __construct(
        private UserServiceInterface $userService,
    ) {
    }

    final public function store(CreateUserRequest $request): ApiResponse
    {
        $createUserDto = config('data_transfer.' . CreateUserDto::class, CreateUserDto::class);

        $user = $this->userService->create(
            data: $createUserDto::from($request)
        );

        return ApiResponse::make(
            data: []
        );
    }
}
