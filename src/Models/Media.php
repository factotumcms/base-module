<?php

namespace Wave8\Factotum\Base\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
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
    ];

    public function fullMediaPath(): string
    {
        return Storage::disk($this->disk)->path($this->path.'/'.$this->file_name);
    }
}
