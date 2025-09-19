<?php

namespace Wave8\Factotum\Base\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\Finder;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    protected string $apiPrefix = 'api/v1/base';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            Route::prefix($this->apiPrefix)
                ->group(function () {
                    $this->registerProtectedApiRoutes();
                    $this->registerPublicApiRoutes();
                });
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(240)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    protected function registerPublicApiRoutes(): void
    {
        Route::group([
            'middleware' => ['api'],
        ], function () {
            $this->mapRoutes(__DIR__.'/../../routes/api/public');
        });
    }

    protected function registerProtectedApiRoutes(): void
    {
        Route::group([
            'middleware' => ['api', 'auth:sanctum'],
        ], function () {
            $this->mapRoutes(__DIR__.'/../../routes/api/protected');
        });
    }

    private function mapRoutes($path): void
    {
        $finder = new Finder;

        $files = $finder->in($path)
            ->files()
            ->name('*.php');

        foreach ($files as $file) {
            $routes = $path.'/'.$file->getFilename();
            require $routes;
        }
    }
}
