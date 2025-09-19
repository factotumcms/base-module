<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Wave8\Factotum\Base\Contracts\Services\UserServiceInterface;
use Wave8\Factotum\Base\Dto\User\CreateUserDto;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Types\Role;

class UserSeeder extends Seeder
{
    public function __construct(
        private readonly UserServiceInterface $userService
    ) {}

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin default user
        Log::info('Creating default admin user..');

        /** @var User $adminUser */
        $adminUser = $this->userService->create(
            data: CreateUserDto::make(
                email: config('factotum_base_config.admin_default.email'),
                password: config('factotum_base_config.admin_default.password'),
                first_name: config('factotum_base_config.admin_default.first_name'),
                last_name: config('factotum_base_config.admin_default.last_name'),
                username: config('factotum_base_config.admin_default.username'),
            )
        );

        $adminUser->assignRole(Role::ADMIN);
    }
}
