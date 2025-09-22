<?php

namespace Wave8\Factotum\Base\Console\Commands;

use Illuminate\Console\Command;
use Wave8\Factotum\Base\Contracts\Services\MediaServiceInterface;
use Wave8\Factotum\Base\Services\MediaService;

class GenerateImagesConversions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'factotum-base:generate-images-conversions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** @var MediaService */
    protected MediaServiceInterface $service;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        GenerateImagesConversions::dispatch()->withoutOverlapping();
    }
}
