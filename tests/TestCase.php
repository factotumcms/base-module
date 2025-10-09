<?php

namespace Wave8\Factotum\Base\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\Permission\PermissionServiceProvider;
use Spatie\TranslationLoader\TranslationServiceProvider;
use Wave8\Factotum\Base\Database\Seeder\DatabaseSeeder;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Providers\ModuleServiceProvider;

#[WithMigration('laravel', 'cache', 'queue')]
#[WithMigration('session')]
abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, WithWorkbench;

    protected $enablesPackageDiscoveries = true;

    protected function getPackageProviders($app): array
    {
        return [
            ModuleServiceProvider::class,
            TranslationServiceProvider::class,
            PermissionServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->defineDatabaseMigrations();
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        $app['config']->set('database.default', 'testing');

        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }


    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations([
            '--database' => 'testing',
        ]);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function defineDatabaseSeeders()
    {
        $this->seed(DatabaseSeeder::class);
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Use test User model for users provider
        $app['config']->set('auth.providers.users.model', User::class);
    }
}
