<?php

use Wave8\Factotum\Base\Dtos\Api\User\CreateUserDto;
use Wave8\Factotum\Base\Dtos\Api\User\UpdateUserDto;
use Wave8\Factotum\Base\Resources\Api\UserResource;

return [
    UserResource::class => UserResource::class,
    CreateUserDto::class => CreateUserDto::class,
    UpdateUserDto::class => UpdateUserDto::class,
];
