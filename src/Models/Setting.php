<?php

namespace Wave8\Factotum\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Setting extends Model
{
    protected $fillable = [
        'type',
        'data_type',
        'group',
        'key',
        'value',
        'user_id',
    ];

    public function users(): belongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
