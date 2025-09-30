<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Wave8\Factotum\Base\Contracts\Services\AuthServiceInterface;
use Wave8\Factotum\Base\Contracts\Services\LanguageServiceInterface;
use Wave8\Factotum\Base\Contracts\Services\MediaServiceInterface;
use Wave8\Factotum\Base\Contracts\Services\PermissionServiceInterface;
use Wave8\Factotum\Base\Contracts\Services\RoleServiceInterface;
use Wave8\Factotum\Base\Contracts\Services\SettingServiceInterface;
use Wave8\Factotum\Base\Contracts\Services\UserServiceInterface;
use Wave8\Factotum\Base\Services\Api\Backoffice\AuthService;
use Wave8\Factotum\Base\Services\Api\Backoffice\LanguageService;
use Wave8\Factotum\Base\Services\Api\Backoffice\MediaService;
use Wave8\Factotum\Base\Services\Api\Backoffice\PermissionService;
use Wave8\Factotum\Base\Services\Api\Backoffice\RoleService;
use Wave8\Factotum\Base\Services\Api\Backoffice\SettingService;
use Wave8\Factotum\Base\Services\Api\Backoffice\UserService;

class AppServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        $this->app->singleton(AuthServiceInterface::class, AuthService::class);
        $this->app->singleton(SettingServiceInterface::class, SettingService::class);
        $this->app->singleton(UserServiceInterface::class, UserService::class);
        $this->app->singleton(RoleServiceInterface::class, RoleService::class);
        $this->app->singleton(PermissionServiceInterface::class, PermissionService::class);
        $this->app->singleton(MediaServiceInterface::class, MediaService::class);
        $this->app->singleton(LanguageServiceInterface::class, LanguageService::class);
    }
}
