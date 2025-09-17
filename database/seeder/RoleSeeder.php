<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Wave8\Factotum\Base\Contracts\Services\RoleServiceInterface;
use Wave8\Factotum\Base\Dto\Role\CreateRoleDto;
use Wave8\Factotum\Base\Types\RoleType;

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
        // Create admin default user
        Log::info('Creating default roles..');

        foreach (RoleType::getValues() as $role) {
            $this->roleService->create(
                data: CreateRoleDto::make(
                    name: $role
                )
            );
        }
    }
}
