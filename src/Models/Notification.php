<?php

namespace Wave8\Factotum\Base\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;
use Wave8\Factotum\Base\Builders\NotificationQueryBuilder;
use Wave8\Factotum\Base\Policies\NotificationPolicy;

#[UsePolicy(NotificationPolicy::class)]
class Notification extends DatabaseNotification
{
    use SoftDeletes;

    protected $fillable = [
        'type',
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
    ];

    public function newEloquentBuilder($query)
    {
        return new NotificationQueryBuilder($query);
    }
}
