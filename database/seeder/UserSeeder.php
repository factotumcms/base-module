<?php

namespace Wave8\Factotum\Base\Database\Seeder;

use Illuminate\Database\Seeder;
use Wave8\Factotum\Base\Contracts\Api\UserServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\User\CreateUserDto;
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
        User::withoutEvents(function () {
            /** @var User $adminUser */
            $adminUser = $this->userService->create(
                data: new CreateUserDto(
                    email: config('factotum_base.admin_default.email'),
                    password: config('factotum_base.admin_default.password'),
                    firstName: config('factotum_base.admin_default.first_name'),
                    lastName: config('factotum_base.admin_default.last_name'),
                    username: config('factotum_base.admin_default.username'),
                )
            );

            $adminUser->assignRole(Role::ADMIN);
        });
    }
}
