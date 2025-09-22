<?php

namespace Wave8\Factotum\Base\Console\Commands;

use Illuminate\Console\Command;
use Wave8\Factotum\Base\Contracts\Services\MediaServiceInterface;
use Wave8\Factotum\Base\Enum\MediaType;
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

    /** @var MediaService $service */
    protected MediaServiceInterface $service;
    /**
     * Execute the console command.
     */
    public function handle()
    {
//        GenerateImagesConversions::dispatch();

        // ***** debug

        $this->service = app()->make(MediaServiceInterface::class);
        $media = $this->service->filter([
            'converted' => false,
            'media_type' => MediaType::IMAGE->value,
        ]);

        foreach ($media as $item) {
            $this->service->generateConversions($item);
        }

        // -------
    }
}
