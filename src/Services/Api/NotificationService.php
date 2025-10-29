<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\NotificationServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Notification\ReadManyNotificationDto;
use Wave8\Factotum\Base\Dtos\Api\Notification\ReadNotificationDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;

class NotificationService implements NotificationServiceInterface
{
    public function show(int $id): ?Model
    {
        return auth()->user()->notifications()->findOrFail($id);
    }

    public function read(int $id, ReadNotificationDto|Data $data): Model
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->read_at = $data->read ? now() : null;
        $notification->save();

        return $notification;
    }

    public function readMany(ReadManyNotificationDto|Data $data): void
    {
        $query = auth()->user()->unreadNotifications()->when($data->ids, function (Builder $query, array $ids) {
            $query->whereIn('id', $ids);
        });

        $query->update([
            'read_at' => $data->read ? now() : null,
        ]);
    }

    public function delete(int $id): void
    {
        auth()->user()->notifications()->findOrFail($id)->delete();
    }

    public function filter(QueryFiltersDto $queryFilters): Paginator|LengthAwarePaginator
    {
        // todo:: implement  notification filtering
    }
}
