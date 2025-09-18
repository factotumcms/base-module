<?php

namespace Wave8\Factotum\Base\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Wave8\Factotum\Base\Models\MediaAsset;

trait HasMediaAssets
{
    public function mediaAssets(): MorphToMany
    {
        return $this->morphToMany(MediaAsset::class, 'model', 'media_asset_model', 'model_id', 'media_asset_id');
    }
}
