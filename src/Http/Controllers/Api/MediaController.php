<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
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
        $this->mediaService->storeFromRequest(
            model: User::find(Auth::id()),
        );

        return ApiResponse::make(
            data: 'ok'
        );
    }

    public function showTest(): ApiResponse
    {
        $this->mediaService->showTest();

        return ApiResponse::make(
            data: 'ok'
        );
    }
}
