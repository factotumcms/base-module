<?php

namespace Wave8\Factotum\Base\Contracts;

use Wave8\Factotum\Base\Dto\UserDto;
use Wave8\Factotum\Base\Models\User;

interface AuthService
{
    public function attemptLogin(UserDto $data): User|false;
}
