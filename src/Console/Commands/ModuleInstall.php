<?php

namespace Wave8\Factotum\Base\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Wave8\Factotum\Base\Database\Seeder\DatabaseSeeder;
use Wave8\Factotum\Base\Models\User;

class ModuleInstall extends Command
{
    private int $processStep = 1;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'factotum-base:install';

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
        $this->printDisclaimer();

        if (! $this->confirm('Do you wish to continue?')) {
            $this->alert('Aborted!');

            return;
        }

        $this->info('*** Factotum Base Module installation started ***');

        $this->setUpEnvironment();
        $this->publishVendorMigrations();
        $this->runMigration();
        $this->seedData();

        $this->info('*** Factotum Base Module installation finished ***');

    }

    private function printDisclaimer(): void
    {
        $this->warn('********************');
        $this->warn('*      DANGER      *');
        $this->warn('********************');
        $this->warn('This command will initialize the Factotum Base Module from scratch. All data will be lost!!');
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
        $this->info("{$this->processStep}) - Seeding database..");
        $this->call('db:seed', [
            '--class' => DatabaseSeeder::class,
        ]);

        $this->processStep++;
    }

    private function publishVendorMigrations(): void
    {
        $this->info("{$this->processStep}) - Publish required vendor migrations..");

        $this->call('vendor:publish', ['--provider' => 'Laravel\Sanctum\SanctumServiceProvider', '--tag' => 'sanctum-migrations']);
        $this->call('vendor:publish', ['--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider', '--tag' => 'medialibrary-migrations']);
        $this->call('vendor:publish', ['--provider' => 'Spatie\TranslationLoader\TranslationServiceProvider', '--tag' => 'translation-loader-migrations']);
        $this->call('vendor:publish', ['--provider' => 'Spatie\Permission\PermissionServiceProvider', '--tag' => 'permission-migrations']);

        $this->processStep++;
    }

    private function setUpEnvironment()
    {
        // Clear previous and Laravel default published migrations
        $directory = database_path('migrations');
        File::delete(File::allFiles($directory));

        // Clear previous and Laravel default models
        $directory = app_path('Models');
        File::delete(File::allFiles($directory));

        // Set up default Auth user model
        // todo:: da implementare
        //        if ( !is_subclass_of(config('auth.providers.users.model'), User::class) ) {
        //            $this->mergeConfigFrom(
        //                __DIR__ . '/../../config/auth-providers.php',
        //                'auth.providers'
        //            );
        //        }
    }
}
