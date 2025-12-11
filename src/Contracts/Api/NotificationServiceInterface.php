<?php

namespace Wave8\Factotum\Base\Contracts\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Dtos\Api\Notification\ReadManyNotificationDto;
use Wave8\Factotum\Base\Dtos\Api\Notification\ReadNotificationDto;

interface NotificationServiceInterface
{
    public function show(int $id): ?Model;

    public function read(int $id, ReadNotificationDto|Data $data): Model;

    public function readMany(ReadManyNotificationDto|Data $data): void;

    public function delete(int $id): void;

    public function filter(): LengthAwarePaginator;
}
