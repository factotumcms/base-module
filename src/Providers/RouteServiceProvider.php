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

    protected array $apiContexts = [];

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        if (env('ENABLE_BACKOFFICE_API')) {
            $this->apiContexts[] = 'backoffice';
        }
        if (env('ENABLE_MOBILE_API')) {
            $this->apiContexts[] = 'mobile';
        }

        $this->configureRateLimiting();

        $this->routes(function () {

            Route::prefix($this->apiPrefix)
                ->group(function () {
                    foreach ($this->apiContexts as $context) {
                        $this->registerProtectedApiRoutes($context);
                        $this->registerPublicApiRoutes($context);
                    }
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

    protected function registerPublicApiRoutes($context): void
    {
        Route::group([
            'middleware' => ['api'],
            'prefix' => $context,
        ], function () use ($context) {
            $this->mapRoutes(__DIR__."/../../routes/api/$context/public");
        });
    }

    protected function registerProtectedApiRoutes(string $context): void
    {
        Route::group([
            'middleware' => ['api', 'auth:sanctum'],
            'prefix' => $context,
        ], function () use ($context) {
            $this->mapRoutes(__DIR__."/../../routes/api/$context/protected");
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
