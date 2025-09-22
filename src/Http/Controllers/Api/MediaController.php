<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use Wave8\Factotum\Base\Contracts\Services\MediaServiceInterface;
use Wave8\Factotum\Base\Dto\Media\StoreFileDto;
use Wave8\Factotum\Base\Enum\Disk;
use Wave8\Factotum\Base\Http\Requests\Api\Media\UploadMediaRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;

final readonly class MediaController
{
    public function __construct(
        private MediaServiceInterface $mediaService,
    ) {}

    public function upload(UploadMediaRequest $request): ApiResponse
    {

        $file = $this->mediaService->store(
            StoreFileDto::make(
                file: $request->file('file'),
                disk: Disk::tryFrom($request->get('disk')),
                path: $request->get('path'),
            )
        );

        return ApiResponse::make(
            data: $file
        );
    }

    public function show(int $id)
    {
        $media = $this->mediaService->show($id);

        $file = Storage::disk($media->disk)->path($media->path.'/'.$media->file_name);

        return response()->file(
            $file,
            ['Content-Type' => 'image/jpeg']
        );

    }
}
