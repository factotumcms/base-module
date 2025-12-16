<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Spatie\TranslationLoader\TranslationServiceProvider;
use Wave8\Factotum\Base\Console\Commands\DispatchGenerateImageConversions;
use Wave8\Factotum\Base\Console\Commands\Install;
use Wave8\Factotum\Base\Console\Commands\PruneModels;
use Wave8\Factotum\Base\Console\Commands\PrunePasswordHistories;
use Wave8\Factotum\Base\Models\Setting;
use Wave8\Factotum\Base\Observers\SettingObserver;
use Wave8\Factotum\Base\Observers\UserObserver;

class ModuleServiceProvider extends LaravelServiceProvider
{
    public function register(): void
    {
        // Register DI services
        $this->app->register(ServiceProvider::class);

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
        $this->configurePublishing();
        $this->configureObservers();
    }

    public function registerCommands(): void
    {
        $this->commands([
            Install::class,
            DispatchGenerateImageConversions::class,
            PrunePasswordHistories::class,
            PruneModels::class,
        ]);
    }

    private function configurePublishing(): void
    {
        $this->publishesMigrations([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'factotum-base-migrations');

        $this->publishes([
            __DIR__.'/../../stubs/app/Providers/FactotumBaseServiceProvider.php' => app_path('Providers/FactotumBaseServiceProvider.php'),
        ], 'factotum-base-provider');

        $this->loadTranslationsFrom(__DIR__.'/../../lang');
    }

    private function configureObservers(): void
    {
        config('auth.providers.users.model')::observe(UserObserver::class);
        Setting::observe(SettingObserver::class);
    }
}
