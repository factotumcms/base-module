<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Wave8\Factotum\Base\Contracts\Api\AuthServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\LanguageServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\MediaServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\PermissionServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\RoleServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\UserServiceInterface;
use Wave8\Factotum\Base\Services\Api\AuthService;
use Wave8\Factotum\Base\Services\Api\LanguageService;
use Wave8\Factotum\Base\Services\Api\MediaService;
use Wave8\Factotum\Base\Services\Api\PermissionService;
use Wave8\Factotum\Base\Services\Api\RoleService;
use Wave8\Factotum\Base\Services\Api\SettingService;
use Wave8\Factotum\Base\Services\Api\UserService;

class ServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        // API Services
        $this->app->singleton(AuthServiceInterface::class, AuthService::class);
        $this->app->singleton(SettingServiceInterface::class, SettingService::class);
        $this->app->singleton(UserServiceInterface::class, UserService::class);
        $this->app->singleton(RoleServiceInterface::class, RoleService::class);
        $this->app->singleton(PermissionServiceInterface::class, PermissionService::class);
        $this->app->singleton(MediaServiceInterface::class, MediaService::class);
        $this->app->singleton(LanguageServiceInterface::class, LanguageService::class);
    }
}
