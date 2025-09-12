<?php

namespace Wave8\Factotum\Base\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Wave8\Factotum\Base\Contracts\Services\SettingServiceInterface;
use Wave8\Factotum\Base\Contracts\Services\UserServiceInterface;
use Wave8\Factotum\Base\Dto\Setting\CreateSettingDto;
use Wave8\Factotum\Base\Dto\User\CreateUserDto;
use Wave8\Factotum\Base\Models\Permission;
use Wave8\Factotum\Base\Models\Role;
use Wave8\Factotum\Base\Types\PermissionType;
use Wave8\Factotum\Base\Types\RoleType;
use Wave8\Factotum\Base\Types\SettingDataType;
use Wave8\Factotum\Base\Types\SettingGroupType;
use Wave8\Factotum\Base\Types\SettingType;
use Wave8\Factotum\Base\Types\SettingTypeType;

class ModuleInstall extends Command
{
    private UserServiceInterface $userService;

    private SettingServiceInterface $settingService;

    private int $processStep = 1;

    public function __construct(
        UserServiceInterface $userService,
        SettingServiceInterface $settingService,

    ) {
        $this->userService = $userService;
        $this->settingService = $settingService;

        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'factotum-base:module-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Factotum Base - Install the Base Module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! $this->confirm('This command will initialize the Factotum Base Module. Do you wish to continue?')) {
            $this->alert('Aborted!');

            return;
        }

        $this->info('--- Factotum Base Module installation started ---');

        $this->runMigration();
        $this->seedData();

        $this->info('--- Factotum Base Module installation finished ---');

    }

    private function runMigration(): void
    {
        // Run migrations
        $this->info("{$this->processStep}) - Running migrations..");
        Artisan::call('migrate:fresh');

        $this->processStep++;
    }

    private function seedData(): void
    {
        // Create a default admin user
        $this->info("{$this->processStep}) - Creating default admin user..");
        $adminUser = $this->userService->create(
            data: CreateUserDto::make(
                email: config('factotum_base_config.admin_default_email'),
                password: config('factotum_base_config.admin_default_password'),
                first_name: 'Agency',
                last_name: 'Dev',
                username: config('factotum_base_config.admin_default_username'),
            )
        );

        // Create roles
        foreach (RoleType::getValues()->filter(fn ($el) => RoleType::from($el) !== RoleType::ADMIN) as $role) {
            Role::create(['name' => $role]);
        }

        $adminRole = Role::create(['name' => RoleType::ADMIN]);
        $adminUser->assignRole(RoleType::ADMIN);

        // Create permissions and assign to admin
        foreach (PermissionType::getValues() as $permission) {
            $perm = Permission::create(['name' => $permission]);
            $adminRole->givePermissionTo($perm);
        }

        // Create settings
        $this->processStep++;
        $this->info("{$this->processStep}) - Creating default settings..");
        $this->settingService->create(
            data: CreateSettingDto::make(
                type: SettingTypeType::SYSTEM,
                data_type: SettingDataType::STRING,
                group: SettingGroupType::AUTH,
                key: SettingType::AUTH_IDENTIFIER,
                value: 'email',
            )
        );

        $this->settingService->create(
            data: CreateSettingDto::make(
                type: SettingTypeType::SYSTEM,
                data_type: SettingDataType::INTEGER,
                group: SettingGroupType::MEDIA,
                key: SettingType::THUMB_SIZE_WIDTH,
                value: 50,
            )
        );

        $this->processStep++;
    }
}
