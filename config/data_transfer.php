<?php

use Wave8\Factotum\Base\Dtos\Api\Auth\LoginUserDto;
use Wave8\Factotum\Base\Dtos\Api\Auth\RegisterUserDto;
use Wave8\Factotum\Base\Dtos\Api\User\CreateUserDto;
use Wave8\Factotum\Base\Dtos\Api\User\UpdateUserDto;
use Wave8\Factotum\Base\Resources\Api\UserResource;

return [
    LoginUserDto::class => LoginUserDto::class,
    RegisterUserDto::class => RegisterUserDto::class,
    UserResource::class => UserResource::class,
    CreateUserDto::class => CreateUserDto::class,
    UpdateUserDto::class => UpdateUserDto::class,
];
