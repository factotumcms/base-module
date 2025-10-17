<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\PermissionServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\RoleServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Permission\CreatePermissionDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Enums\Permission\MediaPermission;
use Wave8\Factotum\Base\Enums\Permission\Permission;
use Wave8\Factotum\Base\Enums\Permission\RolePermission;
use Wave8\Factotum\Base\Enums\Permission\SettingPermission;
use Wave8\Factotum\Base\Enums\Permission\UserPermission;
use Wave8\Factotum\Base\Enums\Role;

class PermissionSeeder extends Seeder
{
    public function __construct(
        private readonly PermissionServiceInterface $permissionService,
        private readonly RoleServiceInterface $roleService
    ) {}

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin default user
        Log::info('Creating default permission..');

        $adminRole = $this->roleService->filter(
            new QueryFiltersDto(
                search: ['name' => Role::ADMIN->value]
            )
        );

        $entities = [
            UserPermission::class,
            MediaPermission::class,
            Permission::class,
            RolePermission::class,
            SettingPermission::class,
        ];

        foreach ($entities as $entity) {
            foreach ($entity::getValues() as $permission) {
                $this->permissionService->create(
                    data: new CreatePermissionDto(
                        name: $permission
                    )
                );

                $adminRole->firstOrFail()->givePermissionTo($permission);
            }
        }

    }
}
