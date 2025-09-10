<?php

namespace Wave8\Factotum\Base\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Wave8\Factotum\Base\Contracts\SettingService;
use Wave8\Factotum\Base\Contracts\UserService;
use Wave8\Factotum\Base\Dto\SettingDto;
use Wave8\Factotum\Base\Dto\UserDto;
use Wave8\Factotum\Base\Models\Permission;
use Wave8\Factotum\Base\Models\Role;
use Wave8\Factotum\Base\Types\BasePermission;
use Wave8\Factotum\Base\Types\BaseRole;
use Wave8\Factotum\Base\Types\BaseSetting;
use Wave8\Factotum\Base\Types\BaseSettingGroup;
use Wave8\Factotum\Base\Types\SettingDataType;
use Wave8\Factotum\Base\Types\SettingType;

class ModuleInstall extends Command
{
    private UserService $userService;

    private SettingService $settingService;

    private int $processStep = 1;

    public function __construct(
        UserService $userService,
        SettingService $settingService,

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

        $this->runVendorPublish();
        $this->runMigration();
        $this->cleanLaravelStubs();
        $this->seedData();

        $this->info('--- Factotum Base Module installation finished ---');

    }

    /**
     * Run the vendor:publish command to publish module assets.
     */
    private function runVendorPublish(): void
    {
        Cache::clear();

        // Publish the module configurations
        $this->info("{$this->processStep}) - Publishing configuration files..");
        Artisan::call('vendor:publish', ['--tag' => 'factotum-base-config', '--force' => true]);
        Artisan::call('vendor:publish', ['--tag' => 'factotum-base-lang', '--force' => true]);

        $this->processStep++;
    }

    private function runMigration(): void
    {
        // Run migrations
        $this->info("{$this->processStep}) - Running migrations..");
        Artisan::call('migrate:fresh');

        $this->processStep++;
    }

    private function cleanLaravelStubs(): void
    {
        // Remove default plain Laravel stubs
        $this->info("{$this->processStep}) - Cleaning default stubs..");
        if (is_file(app_path('Models/User.php'))) {
            unlink(app_path('Models/User.php'));
        }

        $this->processStep++;
    }

    private function seedData(): void
    {
        // Create a default admin user
        $this->info("{$this->processStep}) - Creating default admin user..");
        $adminUser = $this->userService->create(
            data: new UserDto(
                email: config('factotum-base-config.admin_default_email'),
                password: config('factotum-base-config.admin_default_password'),
                username: 'agencydev',
            )
        );

        // Create roles
        foreach (BaseRole::getValues()->filter(fn ($el) => $el !== BaseRole::ADMIN) as $role) {
            Role::create(['name' => $role]);
        }
        $adminRole = Role::create(['name' => BaseRole::ADMIN]);
        $adminUser->assignRole(BaseRole::ADMIN);

        // Create permissions and assign to admin
        foreach (BasePermission::getValues() as $permission) {
            $perm = Permission::create(['name' => $permission]);
            $adminRole->givePermissionTo($perm);
        }

        // Create settings
        $this->processStep++;
        $this->info("{$this->processStep}) - Creating default settings..");
        $this->settingService->create(
            data: new SettingDto(
                type: SettingType::SYSTEM,
                data_type: SettingDataType::STRING,
                group: BaseSettingGroup::AUTH,
                key: BaseSetting::AUTH_IDENTIFIER,
                value: 'email',
            )
        );

        $this->settingService->create(
            data: new SettingDto(
                type: SettingType::SYSTEM,
                data_type: SettingDataType::INTEGER,
                group: BaseSettingGroup::MEDIA,
                key: BaseSetting::THUMB_SIZE_WIDTH,
                value: 50,
            )
        );

        $this->processStep++;
    }
}
