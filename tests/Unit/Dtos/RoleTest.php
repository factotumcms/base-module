<?php

use Wave8\Factotum\Base\Dtos\Api\Role\CreateRoleDto;
use Wave8\Factotum\Base\Dtos\Api\Role\UpdateRoleDto;

describe('RoleDto', function () {
    it('successfully create a new CreateRoleDto instance', function () {

        $dto = new CreateRoleDto(
            name: 'test'
        );

        $dto->guardName = 'web';

        expect($dto)->toBeInstanceOf(CreateRoleDto::class);
    });

    it('successfully create a new UpdateRoleDto instance', function () {

        $dto = new UpdateRoleDto(
            name: 'test', guardName: 'web'
        );

        expect($dto)->toBeInstanceOf(UpdateRoleDto::class);
    });

});
