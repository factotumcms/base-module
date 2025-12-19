<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Wave8\Factotum\Base\Contracts\Api\RoleServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Role\CreateRoleDto;
use Wave8\Factotum\Base\Enums\Role;

class RoleSeeder extends Seeder
{
    public function __construct(
        private readonly RoleServiceInterface $roleService
    ) {}

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (Role::getValues() as $role) {
            $this->roleService->create(
                data: new CreateRoleDto(
                    name: $role
                )
            );
        }
    }
}
