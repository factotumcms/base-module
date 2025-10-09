<?php

namespace Wave8\Factotum\Base\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Wave8\Factotum\Base\Policies\SettingPolicy;

#[UsePolicy(SettingPolicy::class)]
class Setting extends Model
{
    protected $fillable = [
        'scope',
        'data_type',
        'group',
        'key',
        'value',
        'description',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
