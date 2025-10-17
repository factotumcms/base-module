<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Api\Backoffice\AuthServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Auth\LoginUserDto;
use Wave8\Factotum\Base\Dtos\Api\Auth\RegisterUserDto;
use Wave8\Factotum\Base\Http\Requests\Api\Auth\LoginRequest;
use Wave8\Factotum\Base\Http\Requests\Api\Auth\RegisterRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Resources\Api\UserResource;

final readonly class AuthController
{
    public function __construct(
        private AuthServiceInterface $authService
    ) {}

    /**
     * @throws \Exception
     */
    public function login(LoginRequest $request): ApiResponse
    {
        $user = $this->authService->attemptLogin(
            data: LoginUserDto::from($request)
        );

        return ApiResponse::make(
            data: [
                'user' => UserResource::from($user),
                'access_token' => $user->createToken('auth_token')->plainTextToken,
            ],
        );
    }

    public function register(RegisterRequest $request): ApiResponse
    {
        $user = $this->authService->register(
            data: RegisterUserDto::from($request)
        );

        return ApiResponse::make(
            data: [
                'user' => UserResource::from($user),
                'access_token' => $user->createToken('auth_token')->plainTextToken,
            ],
        );
    }
}
