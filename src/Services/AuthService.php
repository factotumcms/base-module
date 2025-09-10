<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Support\Facades\Auth;
use Wave8\Factotum\Base\Contracts\AuthService as AuthServiceContract;
use Wave8\Factotum\Base\Dto\UserDto;
use Wave8\Factotum\Base\Models\User;

class AuthService implements AuthServiceContract
{
    /**
     * @throws \Exception
     */
    public function attemptLogin(UserDto $data): User|false
    {
        try {
            if (! Auth::attempt($data->only('email', 'password')->toArray())) {
                return false;
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return Auth::user()->load(['roles.permissions']);
    }
}
