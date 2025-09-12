<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Wave8\Factotum\Base\Contracts\Services\AuthServiceInterface;
use Wave8\Factotum\Base\Dto\User\CreateUserDto;
use Wave8\Factotum\Base\Http\Requests\Api\Auth\LoginRequest;
use Wave8\Factotum\Base\Http\Responses\Api\Auth\LoginResponse;

final readonly class AuthController
{
    public function __construct(
        private AuthServiceInterface $authService
    ) {}

    /**
     * @throws \Exception
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authService->attemptLogin(
            data: CreateUserDto::from($request->all())
        );

        if (! $user) {
            return LoginResponse::createFailed();
        }

        return LoginResponse::createSuccessful($user);
    }
}
