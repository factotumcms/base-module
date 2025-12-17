<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Http\Request;
use Wave8\Factotum\Base\Contracts\Api\AuthServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Auth\LoginUserDto;
use Wave8\Factotum\Base\Dtos\Api\Auth\RegisterUserDto;
use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Http\Requests\Api\Auth\LoginRequest;
use Wave8\Factotum\Base\Http\Requests\Api\Auth\RegisterRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Resources\Api\UserResource;
use Wave8\Factotum\Base\Services\Api\AuthService;
use Wave8\Factotum\Base\Services\Api\SettingService;

final readonly class AuthController
{
    private string $userResource;

    public function __construct(
        /** @var $authService AuthService */
        private AuthServiceInterface $authService,
        /** @var $settingService SettingService */
        private SettingServiceInterface $settingService,
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

        $tokenExpirationDays = $this->settingService->getValue(
            key: Setting::AUTH_TOKEN_EXPIRATION_DAYS,
            group: SettingGroup::AUTH,
        );

        $isPasswordExpired = $user->isCurrentPasswordExpired();

        $abilities = ['*'];
        if ($isPasswordExpired) {
            $abilities = ['password:change'];
            $metadata['password_change_required'] = true;
        }

        $metadata['access_token'] = $user->createToken(name: 'auth_token', abilities: $abilities, expiresAt: now()->addDays($tokenExpirationDays))->plainTextToken;

        return ApiResponse::make(
            data: $this->userResource::from($user)->additional($metadata)
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

    public function verify(Request $request): ApiResponse
    {
        $this->authService->verifyEmail(
            userId: $request->get('id'),
        );

        return ApiResponse::noContent();
    }
}
