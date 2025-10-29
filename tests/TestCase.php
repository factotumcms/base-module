<?php

namespace Wave8\Factotum\Base\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Wave8\Factotum\Base\Database\Seeder\DatabaseSeeder;

#[WithMigration('laravel', 'cache', 'queue')]
#[WithMigration('notifications')]

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use WithWorkbench;

    protected $enablesPackageDiscoveries = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defineDatabaseMigrations();
    }

    protected function defineDatabaseMigrations()
    {
        $this->artisan('vendor:publish', ['--tag' => 'translation-loader-migrations']);
        $this->artisan('vendor:publish', ['--tag' => 'permission-migrations']);
    }

    protected function defineDatabaseSeeders()
    {
        $this->seed(DatabaseSeeder::class);
    }
}
