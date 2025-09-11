<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class AppServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        $this->app->bind(\Wave8\Factotum\Base\Contracts\Services\AuthServiceInterface::class, \Wave8\Factotum\Base\Services\AuthService::class);
        $this->app->bind(\Wave8\Factotum\Base\Contracts\Services\SettingServiceInterface::class, \Wave8\Factotum\Base\Services\SettingService::class);
        $this->app->bind(\Wave8\Factotum\Base\Contracts\Services\UserServiceInterface::class, \Wave8\Factotum\Base\Services\UserService::class);
    }
}
