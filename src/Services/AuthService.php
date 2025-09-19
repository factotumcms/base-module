<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Support\Facades\Auth;
use Wave8\Factotum\Base\Contracts\Services\AuthServiceInterface;
use Wave8\Factotum\Base\Dto\User\CreateUserDto;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Types\Setting as SettingType;
use Wave8\Factotum\Base\Types\SettingGroup;

class AuthService implements AuthServiceInterface
{
    private SettingService $settingService;

    public function __construct(
        SettingService $settingService,
    ) {
        $this->settingService = $settingService;
    }

    /**
     * @throws \Exception
     */
    public function attemptLogin(CreateUserDto $data): User|false
    {
        try {

            $identifier = $this->settingService->getSystemSettingValue(
                key: SettingType::AUTH_BASIC_IDENTIFIER,
                group: SettingGroup::AUTH,
            );

            if (! Auth::attempt($data->only($identifier, 'password')->toArray())) {
                return false;
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return Auth::user()->load(['roles.permissions']);
    }
}
