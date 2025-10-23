<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ConfigServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('factotum-base.php'),
        ], ['factotum-base-config']);

        $this->mergeConfigFrom(
            __DIR__.'/../../config/config.php',
            'factotum-base'
        );
    }
}
