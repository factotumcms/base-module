<?php

namespace Wave8\Factotum\Base\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;
use Wave8\Factotum\Base\Builders\NotificationQueryBuilder;
use Wave8\Factotum\Base\Enums\Notification\NotificationChannel;
use Wave8\Factotum\Base\Policies\NotificationPolicy;

#[UsePolicy(NotificationPolicy::class)]
class Notification extends DatabaseNotification
{
    use Prunable;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'channel',
        'route',
        'lang',
        'sent_at',
        'response',
        'read_at',
    ];

    protected $casts = [
        'data' => 'json',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'channel' => NotificationChannel::class,
    ];

    public function prunable(): Builder
    {
        return static::onlyTrashed();
    }

    protected function pruning(): void
    {
        // Custom logic before pruning, if needed
    }

    public function newEloquentBuilder($query)
    {
        return new NotificationQueryBuilder($query);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }
}
