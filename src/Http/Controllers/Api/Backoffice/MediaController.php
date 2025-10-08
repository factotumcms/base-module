<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api\Backoffice;

use Wave8\Factotum\Base\Contracts\Api\Backoffice\MediaServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\Media\StoreFileDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Enums\Media\MediaPreset;
use Wave8\Factotum\Base\Helpers\Utility;
use Wave8\Factotum\Base\Http\Requests\Api\Backoffice\Media\UploadMediaRequest;
use Wave8\Factotum\Base\Http\Requests\Api\QueryFiltersRequest;
use Wave8\Factotum\Base\Http\Responses\Api\Backoffice\ApiResponse;
use Wave8\Factotum\Base\Resources\Api\Backoffice\MediaResource;

final readonly class MediaController
{
    public function __construct(
        private MediaServiceInterface $mediaService,
    ) {}

    public function index(QueryFiltersRequest $request): ApiResponse
    {
        $media = $this->mediaService
            ->filter(
                QueryFiltersDto::make(
                    ...Utility::sanitizeQueryString($request->query())
                )
            );

        return ApiResponse::make(
            data: MediaResource::collect($media),
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

    /**
     * Serve the media file identified by the given ID.
     *
     * @param int $id The media record identifier.
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse A file response for the media file with the MIME type set in the Content-Type header.
     */
    public function show(int $id)
    {
        $media = $this->mediaService->show($id);

        return response()->file(
            $media->fullMediaPath(),
            ['Content-Type' => $media->mime_type]
        );
    }
}