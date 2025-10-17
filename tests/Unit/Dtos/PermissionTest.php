<?php

use Wave8\Factotum\Base\Dtos\Api\Permission\CreatePermissionDto;
use Wave8\Factotum\Base\Dtos\Api\Permission\UpdatePermissionDto;

describe('PermissionDto', function () {
    it('successfully create a new CreatePermissionDto instance', function () {

        $dto = new CreatePermissionDto(
            name: 'test'
        );

        $dto->guardName = 'web';

        expect($dto)->toBeInstanceOf(CreatePermissionDto::class);
    });

    it('successfully create a new UpdatePermissionDto instance', function () {

        $dto = new UpdatePermissionDto(
            name: 'test', guardName: 'web'
        );

        expect($dto)->toBeInstanceOf(UpdatePermissionDto::class);
    });

});
