<?php

namespace Wave8\Factotum\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
