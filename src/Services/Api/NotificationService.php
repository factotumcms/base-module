<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\NotificationServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Notification\CreateNotificationDto;
use Wave8\Factotum\Base\Dtos\Api\Notification\ReadManyNotificationDto;
use Wave8\Factotum\Base\Dtos\Api\Notification\ReadNotificationDto;
use Wave8\Factotum\Base\Dtos\Api\QueryPaginationDto;
use Wave8\Factotum\Base\Enums\Notification\NotificationChannel;
use Wave8\Factotum\Base\Models\Notification;

class NotificationService implements NotificationServiceInterface
{
    public function __construct(public readonly Notification $notification) {}

    public function create(CreateNotificationDto $dto): Model
    {
        return $this->notification::create($dto->toArray());
    }
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

    public function filter(QueryPaginationDto $paginationDto): LengthAwarePaginator
    {
        $query = $this->notification->query()
            ->filterByRequest();

        return $query->paginate(
            perPage: $paginationDto->perPage ?? 15,
            page: $paginationDto->page ?? 1,
        );
    }
    public function getBy(string $key, string $value): Collection
    {
        return $this->notification->where($key, $value)->get();
    }

    public function getPendingsByChannel(NotificationChannel $channel): Collection
    {
        return $this->getBy('channel', $channel->value);
    }

    public function elaborate(Notification $notification):bool
    {
        switch ($notification->channel) {
            case NotificationChannel::EMAIL:
                $notifiable = $notification->notifiable_type::find($notification->notifiable_id);

                $notifiable->notify(new $notification->type());
                Log::info("Email notification sent to notifiable ID {$notification->notifiable_id}");
                return true;

            default:
                return false;
        }


    }
}
