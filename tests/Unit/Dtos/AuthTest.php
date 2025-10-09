<?php

use Wave8\Factotum\Base\Dtos\Api\Backoffice\Auth\LoginUserDto;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\Auth\RegisterUserDto;

describe('AuthDto', function () {
    it('it successfully create a new LoginUserDto instance', function () {
        $val = 'test';

        $dto = new LoginUserDto(
            email: $val, password: $val, username: $val
        );

        expect($dto)->toBeInstanceOf(LoginUserDto::class);
    });

    it('it successfully create a new RegisterUserDto instance', function () {
        $val = 'test';

        $dto = new RegisterUserDto(
            email: $val, password: $val, password_confirmation: $val, first_name: $val, last_name: $val, username: $val
        );

        expect($dto)->toBeInstanceOf(RegisterUserDto::class);
    });
});
