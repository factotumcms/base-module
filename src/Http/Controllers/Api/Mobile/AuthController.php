<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api\Mobile;

use Wave8\Factotum\Base\Contracts\Api\Mobile\AuthServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Mobile\Auth\LoginUserDto;
use Wave8\Factotum\Base\Http\Requests\Api\Mobile\Auth\LoginRequest;
use Wave8\Factotum\Base\Http\Responses\Api\Mobile\ApiResponse;
use Wave8\Factotum\Base\Resources\Api\Mobile\UserResource;

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
}
