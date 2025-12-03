<?php

namespace Wave8\Factotum\Base\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Wave8\Factotum\Base\Builders\MediaQueryBuilder;
use Wave8\Factotum\Base\Policies\MediaPolicy;

#[UsePolicy(MediaPolicy::class)]
class Media extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'file_name',
        'mime_type',
        'media_type',
        'presets',
        'disk',
        'path',
        'size',
        'custom_properties',
        'conversions',
    ];

    protected $casts = [
        'conversions' => 'json',
        'presets' => 'array',
        'custom_properties' => 'json',
    ];

    /**
     * Resolve the absolute filesystem path for this media record using its configured disk, path, and file name.
     *
     * @return string The absolute filesystem path to the media file.
     */
    public function fullMediaPath(): string
    {
        return Storage::disk($this->disk)->path($this->path.'/'.$this->file_name);
    }

    public function newEloquentBuilder($query): MediaQueryBuilder
    {
        return new MediaQueryBuilder($query);
    }
}
