<?php

use Wave8\Factotum\Base\Dtos\Api\Backoffice\User\CreateUserDto;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\User\UpdateUserDto;

describe('UserDto', function () {
    it('successfully creates a new CreateUserDto instance', function () {

        $dto = new CreateUserDto(
            email: 'test', password: 'password123', first_name: 'Test', last_name: 'User', username: 'testuser', is_active: true
        );

        expect($dto)->toBeInstanceOf(CreateUserDto::class);
    });

    it('successfully creates a new UpdateUserDto instance', function () {

        $dto = new UpdateUserDto(
            first_name: 'Test', last_name: 'User', is_active: true
        );

        expect($dto)->toBeInstanceOf(UpdateUserDto::class);
    });

});
