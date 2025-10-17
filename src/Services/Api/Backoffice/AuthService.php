<?php

namespace Wave8\Factotum\Base\Services\Api\Backoffice;

use Illuminate\Support\Facades\Auth;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\AuthServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\SettingServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Auth\LoginUserDto;
use Wave8\Factotum\Base\Dtos\Api\Auth\RegisterUserDto;
use Wave8\Factotum\Base\Enums\Setting\Setting as SettingType;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Models\User;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        /** @var $settingService SettingService */
        private readonly SettingServiceInterface $settingService,
    ) {}

    /**
     * @throws \Exception
     */
    public function attemptLogin(LoginUserDto $data): User|false
    {
        $identifier = $this->settingService->getSystemSettingValue(
            key: SettingType::AUTH_BASIC_IDENTIFIER,
            group: SettingGroup::AUTH,
        );

        if (! Auth::attempt($data->only($identifier, 'password')->toArray())) {
            return false;
        }

        return Auth::user()->load([
            'roles' => function ($query) {
                $query->select(['id', 'name']);
            },
            'roles.permissions' => function ($query) {
                $query->select(['id', 'name']);
            },
        ])->setRelation(
            'roles',
            Auth::user()->roles->map(function ($role) {
                $role->makeHidden('pivot');
                $role->permissions->makeHidden('pivot');

                return $role;
            })
        );
    }

    public function register(RegisterUserDto $data): User
    {
        return User::create($data->only('first_name', 'last_name', 'username', 'email', 'password')->toArray());
    }
}
