<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Wave8\Factotum\Base\Console\Commands\ModuleInstall;

class ModuleServiceProvider extends LaravelServiceProvider
{
    public function register(): void
    {
        $this->app->register(AppServiceProvider::class);
        $this->app->register(ConfigServiceProvider::class);
        $this->app->register(LangServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);

        $this->registerCommands();

        $this->loadTranslationsFrom(__DIR__.'/../../lang');

    }

    public function boot(): void
    {
        $this->registerMigrations();
    }

    public function registerCommands(): void
    {
        $this->commands([
            ModuleInstall::class,
        ]);
    }

    public function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}
