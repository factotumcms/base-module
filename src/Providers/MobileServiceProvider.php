<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Wave8\Factotum\Base\Contracts\Api\Mobile\AuthServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\Mobile\SettingServiceInterface;
use Wave8\Factotum\Base\Services\Api\Mobile\AuthService;
use Wave8\Factotum\Base\Services\Api\Mobile\SettingService;

class MobileServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        // Backoffice Services
        $this->app->singleton(AuthServiceInterface::class, AuthService::class);
        $this->app->singleton(SettingServiceInterface::class, SettingService::class);
    }
}
