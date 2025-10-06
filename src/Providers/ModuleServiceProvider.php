<?php

namespace Wave8\Factotum\Base\Providers;

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
        $this->publishesMigrations([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'factotum-base-migrations');
    }

    private function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../lang');
    }
}
