<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Spatie\TranslationLoader\TranslationServiceProvider;
use Wave8\Factotum\Base\Console\Commands\DispatchGenerateImageConversions;
use Wave8\Factotum\Base\Console\Commands\ModuleInstall;

class ModuleServiceProvider extends LaravelServiceProvider
{
    public int $migrationCounter = 0;

    public function register(): void
    {
        // Register DI services
        $this->app->register(BackofficeServiceProvider::class);
        $this->app->register(MobileServiceProvider::class);

        // Register app required service providers
        $this->app->register(ConfigServiceProvider::class);
        $this->app->register(LangServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);

        // Register third party service providers
        $this->app->register(TranslationServiceProvider::class);

        // Register commands
        $this->registerCommands();
    }

    public function boot(): void
    {
        $this->registerMigrations();
        $this->registerTranslations();
    }

    public function registerCommands(): void
    {
        $this->commands([
            ModuleInstall::class,
            DispatchGenerateImageConversions::class,
        ]);
    }

    private function registerMigrations(): void
    {
        //        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([
            __DIR__.'/../../database/migrations/create_users_table.php.stub' => $this->getMigrationFileName('create_users_table.php'),
            __DIR__.'/../../database/migrations/create_settings_table.php.stub' => $this->getMigrationFileName('create_settings_table.php'),
            __DIR__.'/../../database/migrations/create_media_table.php.stub' => $this->getMigrationFileName('create_media_table.php'),
            __DIR__.'/../../database/migrations/create_jobs_table.php.stub' => $this->getMigrationFileName('create_jobs_table.php'),
        ], 'factotum-base-migrations');
    }

    private function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../lang');
    }

    protected function getMigrationFileName(string $migrationFileName): string
    {
        $timestamp = date('Y_m_d_');
        $numPadded = str_pad((string) $this->migrationCounter, 6, '0', STR_PAD_LEFT);

        $filesystem = $this->app->make(Filesystem::class);

        $this->migrationCounter++;

        return Collection::make([$this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR])
            ->flatMap(fn ($path) => $filesystem->glob($path.'*_'.$migrationFileName))
            ->push($this->app->databasePath()."/migrations/{$timestamp}{$numPadded}_{$migrationFileName}")
            ->first();
    }
}
