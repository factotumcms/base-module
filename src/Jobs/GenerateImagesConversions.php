<?php

namespace Wave8\Factotum\Base\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\MediaServiceInterface;
use Wave8\Factotum\Base\Services\Api\Backoffice\MediaService;

class GenerateImagesConversions implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var MediaService $mediaService */
        $mediaService = app()->make(MediaServiceInterface::class);

        $media = $mediaService->getMediaNotConverted();

        foreach ($media as $item) {
            $mediaService->generateConversions($item);
        }

    }
}
