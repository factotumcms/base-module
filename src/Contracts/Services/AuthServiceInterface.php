<?php

namespace Wave8\Factotum\Base\Contracts\Services;

use Wave8\Factotum\Base\Dtos\Api\Backoffice\Auth\LoginUserDto;
use Wave8\Factotum\Base\Models\User;

interface AuthServiceInterface
{
    public function attemptLogin(LoginUserDto $data): User|false;
}
