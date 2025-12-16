<?php

namespace Wave8\Factotum\Base\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Wave8\Factotum\Base\Models\Notification;

class PruneModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'factotum-base:models-prune {--pretend : Show what would be pruned without actually pruning}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune Factotum Base models according to their pruning settings';

    protected array $modelsToPrune = [
        Notification::class,
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call('model:prune', [
            '--model' => $this->modelsToPrune,
            '--pretend' => $this->option('pretend'),
        ], $this->getOutput());
    }
}
