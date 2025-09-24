<?php

namespace Wave8\Factotum\Base\Contracts\Services;

use Wave8\Factotum\Base\Dtos\User\CreateUserDto;
use Wave8\Factotum\Base\Models\User;

interface AuthServiceInterface
{
    public function attemptLogin(CreateUserDto $data): User|false;
}
