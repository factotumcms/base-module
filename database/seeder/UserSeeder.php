<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Wave8\Factotum\Base\Contracts\Services\UserServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\User\CreateUserDto;
use Wave8\Factotum\Base\Enums\Role;
use Wave8\Factotum\Base\Models\User;

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
                email: config('factotum-base.admin_default.email'),
                password: config('factotum-base.admin_default.password'),
                first_name: config('factotum-base.admin_default.first_name'),
                last_name: config('factotum-base.admin_default.last_name'),
                username: config('factotum-base.admin_default.username'),
            )
        );

        $adminUser->assignRole(Role::ADMIN);
    }
}
