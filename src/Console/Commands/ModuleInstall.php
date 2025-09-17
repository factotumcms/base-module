<?php

namespace Wave8\Factotum\Base\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Wave8\Factotum\Base\Database\Seeder\DatabaseSeeder;

class ModuleInstall extends Command
{
    private int $processStep = 1;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'factotum-base:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Factotum Base - Install the Base Module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->printDisclaimer();

        if (! $this->confirm('Do you wish to continue?')) {
            $this->alert('Aborted!');

            return;
        }

        $this->info('*** Factotum Base Module installation started ***');

        $this->runMigration();
        $this->seedData();

        $this->info('*** Factotum Base Module installation finished ***');

    }

    private function printDisclaimer(): void
    {
        $this->warn('********************');
        $this->warn('*      DANGER      *');
        $this->warn('********************');
        $this->warn('This command will initialize the Factotum Base Module from scratch. All data will be lost!!');
    }

    private function runMigration(): void
    {
        // Run migrations
        $this->info("{$this->processStep}) - Running migrations..");
        Artisan::call('migrate:fresh');

        $this->processStep++;
    }

    private function seedData(): void
    {
        $this->info("{$this->processStep}) - Seeding database..");
        $this->call('db:seed', [
            '--class' => DatabaseSeeder::class,
        ]);

        $this->processStep++;
    }
}
