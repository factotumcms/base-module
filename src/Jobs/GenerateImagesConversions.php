<?php

namespace Wave8\Factotum\Base\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Wave8\Factotum\Base\Contracts\Services\MediaServiceInterface;
use Wave8\Factotum\Base\Enum\MediaType;
use Wave8\Factotum\Base\Services\MediaService;

class GenerateImagesConversions implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        /** @var MediaService */
        private MediaServiceInterface $service,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $media = $this->service->filter([
            'converted' => false,
            'media_type' => MediaType::IMAGE->value,
        ]);

    }
}
