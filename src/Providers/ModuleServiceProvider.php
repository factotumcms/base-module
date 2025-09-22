<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Spatie\TranslationLoader\TranslationServiceProvider;
use Wave8\Factotum\Base\Console\Commands\GenerateImagesConversions;
use Wave8\Factotum\Base\Console\Commands\ModuleInstall;

class ModuleServiceProvider extends LaravelServiceProvider
{
    public function register(): void
    {
        // Register DI services
        $this->app->register(AppServiceProvider::class);

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
            GenerateImagesConversions::class,
        ]);
    }

    private function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    private function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../lang');
    }
}
