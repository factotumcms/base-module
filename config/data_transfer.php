<?php

use Wave8\Factotum\Base\Dtos\Api\Auth\LoginUserDto;
use Wave8\Factotum\Base\Dtos\Api\Auth\RegisterUserDto;
use Wave8\Factotum\Base\Dtos\Api\Role\CreateRoleDto;
use Wave8\Factotum\Base\Dtos\Api\Role\UpdateRoleDto;
use Wave8\Factotum\Base\Dtos\Api\User\CreateUserDto;
use Wave8\Factotum\Base\Dtos\Api\User\UpdateUserDto;
use Wave8\Factotum\Base\Resources\Api\RoleResource;
use Wave8\Factotum\Base\Resources\Api\UserResource;

return [
    // Dto Bindings
    LoginUserDto::class => LoginUserDto::class,
    RegisterUserDto::class => RegisterUserDto::class,
    CreateUserDto::class => CreateUserDto::class,
    UpdateUserDto::class => UpdateUserDto::class,
    CreateRoleDto::class => CreateRoleDto::class,
    UpdateRoleDto::class => UpdateRoleDto::class,

    // Resource Bindings
    UserResource::class => UserResource::class,
    RoleResource::class => RoleResource::class,
];
