<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class LangServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        $this->publishes([
            __DIR__.'/../../lang' => base_path('lang'),
        ], ['factotum-base-lang']);

    }
}
