<?php

use Wave8\Factotum\Base\Dtos\Api\Auth\LoginUserDto;
use Wave8\Factotum\Base\Dtos\Api\Auth\RegisterUserDto;

describe('AuthDto', function () {
    it('successfully create a new LoginUserDto instance', function () {
        $val = 'test';

        $dto = new LoginUserDto(
            email: $val, password: $val,
        );

        $dto->username = $val;

        expect($dto)->toBeInstanceOf(LoginUserDto::class);
    });

    it('successfully create a new RegisterUserDto instance', function () {
        $val = 'test';

        $dto = new RegisterUserDto(
            email: $val, password: $val, passwordConfirmation: $val, firstName: $val, lastName: $val
        );

        $dto->username = $val;

        expect($dto)->toBeInstanceOf(RegisterUserDto::class);
    });
});
