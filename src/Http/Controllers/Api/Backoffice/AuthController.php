<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api\Backoffice;

use Wave8\Factotum\Base\Contracts\Api\Backoffice\AuthServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\Auth\LoginUserDto;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\Auth\RegisterUserDto;
use Wave8\Factotum\Base\Http\Requests\Api\Backoffice\Auth\LoginRequest;
use Wave8\Factotum\Base\Http\Requests\Api\Backoffice\Auth\RegisterRequest;
use Wave8\Factotum\Base\Http\Responses\Api\Backoffice\ApiResponse;
use Wave8\Factotum\Base\Resources\Api\Backoffice\UserResource;

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
            data: LoginUserDto::make(...$request->all())
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
            data: RegisterUserDto::make(...$request->all())
        );

        return ApiResponse::make(
            data: [
                'user' => UserResource::from($user),
                'access_token' => $user->createToken('auth_token')->plainTextToken,
            ],
        );
    }
}
