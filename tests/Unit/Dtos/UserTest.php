<?php

use Wave8\Factotum\Base\Dtos\Api\User\CreateUserDto;
use Wave8\Factotum\Base\Dtos\Api\User\UpdateUserDto;

describe('UserDto', function () {
    it('successfully creates a new CreateUserDto instance', function () {
        $dto = new CreateUserDto(
            email: 'test', password: 'password123', firstName: 'Test', lastName: 'User', username: 'testuser', isActive: true
        );

        expect($dto)->toBeInstanceOf(CreateUserDto::class);
    });

    it('successfully creates a new UpdateUserDto instance', function () {
        $dto = new UpdateUserDto(
            firstName: 'Test', lastName: 'User', isActive: true
        );

        expect($dto)->toBeInstanceOf(UpdateUserDto::class);
    });
});
