<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Support\Facades\Gate;
use Wave8\Factotum\Base\Contracts\Services\MediaServiceInterface;
use Wave8\Factotum\Base\Dto\Media\StoreFileDto;
use Wave8\Factotum\Base\Enum\MediaPreset;
use Wave8\Factotum\Base\Http\Requests\Api\Media\UploadMediaRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Models\Media;

final readonly class MediaController
{
    public function __construct(
        private MediaServiceInterface $mediaService,
    ) {}

    public function upload(UploadMediaRequest $request): ApiResponse
    {
        Gate::authorize('upload', Media::class);

        $file = $this->mediaService->store(
            StoreFileDto::make(
                file: $request->file('file'),
                presets: [MediaPreset::THUMBNAIL]
            )
        );

        return ApiResponse::make(
            data: $file
        );
    }

    public function show(int $id)
    {
        Gate::authorize('read', Media::class);

        $media = $this->mediaService->show($id);

        return response()->file(
            $this->mediaService->getFullMediaPath($media),
            ['Content-Type' => $media->mime_type]
        );

    }
}
