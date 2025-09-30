<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api\Backoffice;

use Wave8\Factotum\Base\Contracts\Services\AuthServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\User\CreateUserDto;
use Wave8\Factotum\Base\Http\Requests\Api\Backoffice\Auth\LoginRequest;
use Wave8\Factotum\Base\Http\Responses\Api\Backoffice\ApiResponse;
use Wave8\Factotum\Base\Resources\UserResource;

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
            data: CreateUserDto::from($request->all())
        );

        return ApiResponse::make(
            data: [
                'user' => UserResource::from($user),
                'access_token' => $user->createToken('auth_token')->plainTextToken,
            ],
        );
    }
}
