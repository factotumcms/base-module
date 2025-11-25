<?php

namespace Wave8\Factotum\Base\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Wave8\Factotum\Base\Builders\SettingQueryBuilder;
use Wave8\Factotum\Base\Enums\Setting\SettingDataType;
use Wave8\Factotum\Base\Policies\SettingPolicy;

#[UsePolicy(SettingPolicy::class)]
class Setting extends Model
{
    protected $fillable = [
        'is_editable',
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

    public function newEloquentBuilder($query)
    {
        return new SettingQueryBuilder($query);
    }

    protected function casts(): array
    {
        return [
            'data_type' => SettingDataType::class,
            'is_editable' => 'bool',
        ];
    }
}
