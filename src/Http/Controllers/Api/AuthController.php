<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Api\AuthServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Auth\LoginUserDto;
use Wave8\Factotum\Base\Dtos\Api\Auth\RegisterUserDto;
use Wave8\Factotum\Base\Http\Requests\Api\Auth\LoginRequest;
use Wave8\Factotum\Base\Http\Requests\Api\Auth\RegisterRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Resources\Api\UserResource;
use Wave8\Factotum\Base\Services\Api\AuthService;

final readonly class AuthController
{
    private string $userResource;

    public function __construct(
        /** @var $authService AuthService */
        private AuthServiceInterface $authService
    ) {
        $this->userResource = config('data_transfer.'.UserResource::class);
    }

    /**
     * @throws \Exception
     */
    public function login(LoginRequest $request): ApiResponse
    {
        $loginUserDto = config('data_transfer.'.LoginUserDto::class);

        $user = $this->authService->attemptLogin(
            data: $loginUserDto::from($request)
        );

        $user->load('avatar', 'roles.permissions');

        return ApiResponse::make(
            data: [
                'user' => $this->userResource::from($user),
                'access_token' => $user->createToken('auth_token')->plainTextToken,
            ],
        );
    }

    public function logout(): ApiResponse
    {
        $this->authService->logout();

        return ApiResponse::noContent();
    }

    public function register(RegisterRequest $request): ApiResponse
    {
        $registerUserDto = config('data_transfer.'.RegisterUserDto::class);

        $user = $this->authService->register(
            data: $registerUserDto::from($request)
        );

        return ApiResponse::make(
            data: $this->userResource::from($user),
            status: ApiResponse::HTTP_CREATED
        );
    }
}
