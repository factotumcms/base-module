<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Spatie\TranslationLoader\TranslationServiceProvider;
use Wave8\Factotum\Base\Console\Commands\DispatchGenerateImageConversions;
use Wave8\Factotum\Base\Console\Commands\ModuleInstall;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\SettingServiceInterface;
use Wave8\Factotum\Base\Services\Api\Backoffice\SettingService;

class ModuleServiceProvider extends LaravelServiceProvider
{
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
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    private function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../lang');
    }
}
