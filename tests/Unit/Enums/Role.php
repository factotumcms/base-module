<?php
describe('RoleEnum', function () {
    it('check RoleEnum has exact values', function () {

        $values = array_flip(\Wave8\Factotum\Base\Enums\Role::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'admin',
            'editor',
        ]);

    });
});
