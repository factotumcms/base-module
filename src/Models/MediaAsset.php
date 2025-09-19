<?php

namespace Wave8\Factotum\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaAsset extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(
            User::class, 'model', 'media_asset_model', 'media_asset_id', 'model_id'
        )->withTimestamps();
    }
}
