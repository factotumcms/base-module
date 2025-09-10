<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Support\Facades\Auth;
use Wave8\Factotum\Base\Contracts\AuthService as AuthServiceContract;
use Wave8\Factotum\Base\Dto\UserDto;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Types\BaseSetting;
use Wave8\Factotum\Base\Types\BaseSettingGroup;

class AuthService implements AuthServiceContract
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
    public function attemptLogin(UserDto $data): User|false
    {
        try {

            $identifier = $this->settingService->getSystemSettingValue(
                key: BaseSetting::AUTH_IDENTIFIER,
                group: BaseSettingGroup::AUTH,
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
