<?php

namespace Wave8\Factotum\Base\Contracts\Api;

use Wave8\Factotum\Base\Dtos\Api\Auth\LoginUserDto;
use Wave8\Factotum\Base\Models\User;

interface AuthServiceInterface
{
    public function attemptLogin(LoginUserDto $data): User|false;
}
