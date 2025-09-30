<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Wave8\Factotum\Base\Contracts\Services\PermissionServiceInterface;
use Wave8\Factotum\Base\Contracts\Services\RoleServiceInterface;
use Wave8\Factotum\Base\Dtos\Permission\CreatePermissionDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Enums\Permission;
use Wave8\Factotum\Base\Models\Role;

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
            QueryFiltersDto::make(
                search: ['name' => \Wave8\Factotum\Base\Enums\Role::ADMIN->value]
            )
        );

        foreach (Permission::getValues() as $permission) {
            $this->permissionService->create(
                data: CreatePermissionDto::make(
                    name: $permission
                )
            );

            $adminRole->firstOrFail()->givePermissionTo($permission);
        }

    }
}
