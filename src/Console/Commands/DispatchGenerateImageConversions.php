<?php

namespace Wave8\Factotum\Base\Console\Commands;

use Illuminate\Console\Command;
use Wave8\Factotum\Base\Jobs\GenerateImagesConversions;

class DispatchGenerateImageConversions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'factotum-base:dispatch-generate-images-conversions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch job to generate image conversions for media items without conversions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        GenerateImagesConversions::dispatch();
    }
}
