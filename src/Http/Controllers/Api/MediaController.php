<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Wave8\Factotum\Base\Contracts\Services\MediaServiceInterface;
use Wave8\Factotum\Base\Http\Requests\Api\Media\UploadMediaRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Models\User;

final readonly class MediaController
{
    public function __construct(
        private MediaServiceInterface $mediaService,
    ) {}

    public function upload(UploadMediaRequest $request): ApiResponse
    {
        // todo:: da capire come utilizzare il mode, creare api specifiche per le diverse entità
        $this->mediaService->storeFromRequest(
            model: User::find(Auth::id()),
        );

        return ApiResponse::make(
            data: 'ok'
        );
    }

    public function show(string $uuid)
    {
        /** @var Media $media */
        $media = $this->mediaService->retrieveByUuid($uuid)->media[0];

        return $media;
    }
}
