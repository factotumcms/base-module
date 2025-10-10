<?php

use Wave8\Factotum\Base\Dtos\Api\Backoffice\Role\CreateRoleDto;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\Role\UpdateRoleDto;

describe('RoleDto', function () {
    it('successfully create a new CreateRoleDto instance', function () {

        $dto = new CreateRoleDto(
            name: 'test', guard_name: 'web'
        );

        expect($dto)->toBeInstanceOf(CreateRoleDto::class);
    });

    it('successfully create a new UpdateRoleDto instance', function () {

        $dto = new UpdateRoleDto(
            name: 'test', guard_name: 'web'
        );

        expect($dto)->toBeInstanceOf(UpdateRoleDto::class);
    });

});
