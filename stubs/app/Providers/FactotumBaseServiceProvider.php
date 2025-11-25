<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Wave8\Factotum\Base\Models\User;

final class FactotumBaseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerModelBindings();
        $this->registerServiceBindings();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    private function registerModelBindings(): void
    {
        $this->app->bind(User::class, \App\Models\User::class);
    }

    private function registerServiceBindings(): void
    {
        // Example
        // $this->app->singleton(\Wave8\Factotum\Base\Contracts\Api\UserServiceInterface::class, \App\Services\Api\UserService::class);
    }
}
