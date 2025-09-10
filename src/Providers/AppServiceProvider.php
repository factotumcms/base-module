<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class AppServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        $this->app->bind(\Wave8\Factotum\Base\Contracts\AuthService::class, \Wave8\Factotum\Base\Services\AuthService::class);
        $this->app->bind(\Wave8\Factotum\Base\Contracts\UserService::class, \Wave8\Factotum\Base\Services\UserService::class);
        $this->app->bind(\Wave8\Factotum\Base\Contracts\SettingService::class, \Wave8\Factotum\Base\Services\SettingService::class);
    }
}
