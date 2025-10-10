<?php

use Wave8\Factotum\Base\Enums\Role;

describe('RoleEnum', function () {
    it('check RoleEnum has exact values', function () {

        $values = array_flip(Role::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'admin',
            'editor',
        ]);

    });
});
