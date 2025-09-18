<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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

        $model->mediaAssets()->attach($mediaAssets->id);

        $mediaAssets->media_id = $media->id;
        $mediaAssets->save();
    }

    public function showTest()
    {
        $user = Auth::user();
        $mediaAssets = $user->mediaAssets();

        foreach ($user->mediaAssets()->get() as $mediaAsset) {
            //            $test = $mediaAsset->getMedia('*')->first()->getUrl();
            $test = $mediaAsset->users()->get();
            dd($test);
        }

    }
}
