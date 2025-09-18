<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Wave8\Factotum\Base\Contracts\Services\AuthServiceInterface;
use Wave8\Factotum\Base\Contracts\Services\MediaServiceInterface;
use Wave8\Factotum\Base\Contracts\Services\PermissionServiceInterface;
use Wave8\Factotum\Base\Contracts\Services\RoleServiceInterface;
use Wave8\Factotum\Base\Contracts\Services\SettingServiceInterface;
use Wave8\Factotum\Base\Contracts\Services\UserServiceInterface;
use Wave8\Factotum\Base\Services\AuthService;
use Wave8\Factotum\Base\Services\MediaService;
use Wave8\Factotum\Base\Services\PermissionService;
use Wave8\Factotum\Base\Services\RoleService;
use Wave8\Factotum\Base\Services\SettingService;
use Wave8\Factotum\Base\Services\UserService;

class AppServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(SettingServiceInterface::class, SettingService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);
        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);
        $this->app->bind(MediaServiceInterface::class, MediaService::class);
    }
}
