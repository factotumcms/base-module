<?php

namespace Wave8\Factotum\Base\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\TranslationLoader\TranslationServiceProvider;
use Wave8\Factotum\Base\Database\Seeder\DatabaseSeeder;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Providers\ModuleServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected $enablesPackageDiscoveries = true;

    protected function getPackageProviders($app): array
    {
        return [
            ModuleServiceProvider::class,
            TranslationServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        // Code before application created.

        $this->afterApplicationCreated(function () {
            $this->seed(DatabaseSeeder::class);
        });

        $this->beforeApplicationDestroyed(function () {
            // Code before application destroyed.
        });

        parent::setUp();
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }


    protected function getEnvironmentSetUp($app): void
    {
        // Use test User model for users provider
        $app['config']->set('auth.providers.users.model', User::class);
    }
}
