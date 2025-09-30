<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\AuthServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\LanguageServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\MediaServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\PermissionServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\RoleServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\SettingServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\UserServiceInterface;
use Wave8\Factotum\Base\Services\Api\Backoffice\AuthService;
use Wave8\Factotum\Base\Services\Api\Backoffice\LanguageService;
use Wave8\Factotum\Base\Services\Api\Backoffice\MediaService;
use Wave8\Factotum\Base\Services\Api\Backoffice\PermissionService;
use Wave8\Factotum\Base\Services\Api\Backoffice\RoleService;
use Wave8\Factotum\Base\Services\Api\Backoffice\SettingService;
use Wave8\Factotum\Base\Services\Api\Backoffice\UserService;

class BackofficeServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        // Backoffice Services
        $this->app->singleton(AuthServiceInterface::class, AuthService::class);
        $this->app->singleton(SettingServiceInterface::class, SettingService::class);
        $this->app->singleton(UserServiceInterface::class, UserService::class);
        $this->app->singleton(RoleServiceInterface::class, RoleService::class);
        $this->app->singleton(PermissionServiceInterface::class, PermissionService::class);
        $this->app->singleton(MediaServiceInterface::class, MediaService::class);
        $this->app->singleton(LanguageServiceInterface::class, LanguageService::class);
    }
}
