<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Wave8\Factotum\Base\Contracts\AuthService;
use Wave8\Factotum\Base\Dto\UserDto;
use Wave8\Factotum\Base\Http\Requests\Api\Auth\LoginRequest;
use Wave8\Factotum\Base\Http\Responses\Api\Auth\LoginResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    /**
     * @throws \Exception
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authService->attemptLogin(
            data: UserDto::from($request->all())
        );

        if (! $user) {
            return LoginResponse::createFailed();
        }

        return LoginResponse::createSuccessful($user);
    }
}
