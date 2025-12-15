<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Support\Facades\Auth;
use Wave8\Factotum\Base\Contracts\Api\AuthServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\UserServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Auth\LoginUserDto;
use Wave8\Factotum\Base\Dtos\Api\Auth\RegisterUserDto;
use Wave8\Factotum\Base\Enums\Setting\Setting as SettingType;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Exceptions\AuthenticationException;
use Wave8\Factotum\Base\Models\User;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        /** @var $settingService SettingService */
        private readonly SettingServiceInterface $settingService,
        /** @var $userService UserService */
        private readonly UserServiceInterface $userService,
    ) {}

    /**
     * @throws \Exception
     */
    public function attemptLogin(LoginUserDto $data): User|false
    {
        $identifier = $this->settingService->getValue(
            key: SettingType::AUTH_BASIC_IDENTIFIER,
            group: SettingGroup::AUTH,
        );

        $credentials = array_merge($data->only($identifier, 'password')->toArray(), ['is_active' => true]);

        if (! Auth::once($credentials)) {
            throw new AuthenticationException;
        }

        $this->updateLastLogin();
        $this->cleanUpOldTokens();

        return Auth::user();
    }

    public function register(RegisterUserDto $data): User
    {
        return User::create(
            $data->toArray()
        );
    }

    public function logout(): void
    {
        $this->cleanUpOldTokens();
    }

    public function verifyEmail(int $userId): bool
    {
        $user = $this->userService->read($userId);

        $user->email_verified_at = now();

        return $user->save();
    }

    private function updateLastLogin(): void
    {
        $user = Auth::user();
        $user->last_login_at = now();

        $user->save();
    }

    private function cleanUpOldTokens()
    {
        Auth::user()->tokens()->where('created_at', '<', now()->addSecond())->delete();
    }
}
