<?php

use Wave8\Factotum\Base\Enums\Permission\MediaPermission;
use Wave8\Factotum\Base\Enums\Permission\Permission;
use Wave8\Factotum\Base\Enums\Permission\RolePermission;
use Wave8\Factotum\Base\Enums\Permission\SettingPermission;
use Wave8\Factotum\Base\Enums\Permission\UserPermission;

describe('PermissionEnums', function () {
    it('check MediaPermission has exact values', function () {
        $values = array_flip(MediaPermission::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'upload_media',
            'read_media',
            'delete_media',
        ]);
    });

    it('check Permission has exact values', function () {
        $values = array_flip(Permission::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'read_permissions',
        ]);
    });

    it('check RolePermission has exact values', function () {
        $values = array_flip(RolePermission::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'create_role',
            'read_role',
            'update_role',
            'delete_role',
        ]);
    });

    it('check SettingPermission has exact values', function () {
        $values = array_flip(SettingPermission::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'read_settings',
            'update_settings',
        ]);
    });

    it('check UserPermission has exact values', function () {
        $values = array_flip(UserPermission::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'create_user',
            'read_user',
            'edit_users',
            'delete_users',
        ]);
    });
});
