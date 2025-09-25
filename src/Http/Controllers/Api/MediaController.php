<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Services\MediaServiceInterface;
use Wave8\Factotum\Base\Dtos\Media\StoreFileDto;
use Wave8\Factotum\Base\Enums\MediaPreset;
use Wave8\Factotum\Base\Http\Requests\Api\Media\UploadMediaRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Resources\MediaResource;

final readonly class MediaController
{
    public function __construct(
        private MediaServiceInterface $mediaService,
    ) {}

    public function index(): ApiResponse
    {
        $media = $this->mediaService->getAll();

        return ApiResponse::make(
            data: MediaResource::collect($media),
            //            data: $media->map(fn ($el) => MediaResource::from($el)),
        );
    }

    public function create(UploadMediaRequest $request): ApiResponse
    {
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
        $media = $this->mediaService->show($id);

        return response()->file(
            $this->mediaService->getFullMediaPath($media),
            ['Content-Type' => $media->mime_type]
        );
    }
}
