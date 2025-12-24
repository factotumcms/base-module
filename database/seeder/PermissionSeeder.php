<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Wave8\Factotum\Base\Contracts\Api\PermissionServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Permission\CreatePermissionDto;
use Wave8\Factotum\Base\Enums\Permission\MediaPermission;
use Wave8\Factotum\Base\Enums\Permission\NotificationPermission;
use Wave8\Factotum\Base\Enums\Permission\Permission;
use Wave8\Factotum\Base\Enums\Permission\RolePermission;
use Wave8\Factotum\Base\Enums\Permission\SettingPermission;
use Wave8\Factotum\Base\Enums\Permission\UserPermission;
use Wave8\Factotum\Base\Enums\Role as RoleEnum;
use Wave8\Factotum\Base\Models\Role;

class PermissionSeeder extends Seeder
{
    public function __construct(
        private readonly PermissionServiceInterface $permissionService,
    ) {}

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', RoleEnum::ADMIN->value)->firstOrFail();

        $entities = [
            UserPermission::class,
            MediaPermission::class,
            Permission::class,
            RolePermission::class,
            SettingPermission::class,
            NotificationPermission::class,
        ];

        foreach ($entities as $entity) {
            foreach ($entity::getValues() as $permission) {
                $this->permissionService->create(
                    data: new CreatePermissionDto(
                        name: $permission
                    )
                );

                $adminRole->givePermissionTo($permission);
            }
        }
    }
}
