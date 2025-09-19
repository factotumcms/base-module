<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Database\Eloquent\Model;
use Wave8\Factotum\Base\Contracts\Services\MediaServiceInterface;
use Wave8\Factotum\Base\Models\MediaAsset;

class MediaService implements MediaServiceInterface
{
    public function storeFromRequest(?Model $model)
    {
        $mediaAssets = new MediaAsset;

        $media = $mediaAssets->addMediaFromRequest('file')->toMediaCollection('images');

        $mediaAssets->media_id = $media->id;
        $mediaAssets->save();

        $model?->mediassets()->attach($mediaAssets->id);

        $mediaAssets->media_id = $media->id;
        $mediaAssets->save();
    }

    public function retrieveByUuid(string $uuid): MediaAsset
    {
        return MediaAsset::whereHas('media', function ($query) use ($uuid) {
            $query->where('uuid', $uuid);
        })->firstOrFail();
    }
}
