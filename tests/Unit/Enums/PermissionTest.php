<?php
describe('PermissionEnums', function () {
    it('check MediaPermission has exact values', function () {

        $values = array_flip(\Wave8\Factotum\Base\Enums\Permission\MediaPermission::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'upload_media',
            'read_media',
        ]);

    });

    it('check Permission has exact values', function () {

        $values = array_flip(\Wave8\Factotum\Base\Enums\Permission\Permission::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'read_permissions'
        ]);

    });

    it('check RolePermission has exact values', function () {

        $values = array_flip(\Wave8\Factotum\Base\Enums\Permission\RolePermission::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'create_role',
            'read_role',
            'update_role',
            'delete_role',
        ]);

    });

    it('check SettingPermission has exact values', function () {

        $values = array_flip(\Wave8\Factotum\Base\Enums\Permission\SettingPermission::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'read_settings',
            'update_settings',
        ]);

    });

    it('check UserPermission has exact values', function () {

        $values = array_flip(\Wave8\Factotum\Base\Enums\Permission\UserPermission::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'create_user',
            'read_user',
            'edit_users',
            'delete_users',
        ]);

    });
});
