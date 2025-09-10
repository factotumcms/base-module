<?php

namespace Wave8\Factotum\Base\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Wave8\Factotum\Base\Contracts\SettingService;
use Wave8\Factotum\Base\Contracts\UserService;
use Wave8\Factotum\Base\Dto\SettingDto;
use Wave8\Factotum\Base\Dto\UserDto;
use Wave8\Factotum\Base\Models\Permission;
use Wave8\Factotum\Base\Models\Role;
use Wave8\Factotum\Base\Types\BaseSetting;
use Wave8\Factotum\Base\Types\BaseSettingGroup;
use Wave8\Factotum\Base\Types\SettingDataType;
use Wave8\Factotum\Base\Types\SettingType;

class ModuleInstall extends Command
{
    private UserService $userService;

    private SettingService $settingService;

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
        //        if(!$this->confirm('This command will initialize the Factotum Base Module. Do you wish to continue?')) {
        //            return;
        //        }

        $this->info('Factotum Base Module installation started');

        $this->runVendorPublish();
        $this->runMigration();
        $this->cleanLaravelStubs();
        $this->seedData();
    }

    /**
     * Run the vendor:publish command to publish module assets.
     */
    private function runVendorPublish(): void
    {
        // Publish the module configurations
        $this->info('Publishing configuration files..');
        Artisan::call('vendor:publish', ['--tag' => 'factotum-base-config', '--force' => true]);
        Artisan::call('vendor:publish', ['--tag' => 'factotum-base-lang', '--force' => true]);
    }

    private function runMigration(): void
    {
        // Run migrations
        $this->info('Running migrations..');
        Artisan::call('migrate:fresh');
    }

    private function cleanLaravelStubs(): void
    {
        // Remove default plain Laravel stubs
        $this->info('Cleaning default stubs..');
        if (is_file(app_path('Models/User.php'))) {
            unlink(app_path('Models/User.php'));
        }
    }

    private function seedData(): void
    {
        // Create a default admin user
        $this->info('Creating default admin user..');
        $adminUser = $this->userService->create(
            data: new UserDto(
                email: config('factotum-base-config.admin_default_email'),
                password: config('factotum-base-config.admin_default_password'),
                username: 'agencydev',
            )
        );

        $adminRole = Role::create(['name' => 'admin']);
        Permission::create(['name' => 'can_edit_users']);

        $adminRole->givePermissionTo('can_edit_users');
        $adminUser->assignRole('admin');

        // Create settings
        $this->info('Creating default settings..');
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

        $setting = $this->settingService->create(
            data: new SettingDto(
                type: SettingType::SYSTEM,
                data_type: SettingDataType::JSON,
                group: BaseSettingGroup::MEDIA,
                key: BaseSetting::THUMB_QUALITY,
                value: json_encode(['jpg' => 90, 'png' => 9])
            )
        );

        $adminUser->settings()->attach($setting->id, ['value' => 90]);
    }
}
