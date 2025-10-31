<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Api\MediaServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Media\StoreFileDto;
use Wave8\Factotum\Base\Enums\Media\MediaPreset;
use Wave8\Factotum\Base\Http\Requests\Api\Media\UploadMediaRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Models\Media;
use Wave8\Factotum\Base\Resources\Api\MediaResource;
use Wave8\Factotum\Base\Services\Api\MediaService;

final readonly class MediaController
{
    public string $mediaResource;

    public function __construct(
        /** @var $mediaService MediaService */
        private MediaServiceInterface $mediaService,
    ) {
        $this->mediaResource = config('data_transfer.'.MediaResource::class);
    }

    public function index(): ApiResponse
    {
        $media = $this->mediaService->filter();

        return ApiResponse::make(
            data: $this->mediaResource::collect($media),
        );
    }

    public function create(UploadMediaRequest $request): ApiResponse
    {
        $storeFileDto = config('data_transfer.'.StoreFileDto::class);

        $file = $this->mediaService->store(
            new $storeFileDto(
                file: $request->file('file'),
                presets: [MediaPreset::THUMBNAIL]
            )
        );

        return ApiResponse::make(
            data: $file, status: ApiResponse::HTTP_CREATED
        );
    }

    public function show(Media $media)
    {
        return response()->file(
            $media->fullMediaPath(),
            ['Content-Type' => $media->mime_type]
        );
    }
}
