<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Api\NotificationServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Notification\ReadManyNotificationDto;
use Wave8\Factotum\Base\Dtos\Api\Notification\ReadNotificationDto;
use Wave8\Factotum\Base\Http\Requests\Api\Notification\ReadManyNotificationRequest;
use Wave8\Factotum\Base\Http\Requests\Api\Notification\ReadNotificationRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Models\Notification;
use Wave8\Factotum\Base\Resources\Api\NotificationResource;

final readonly class NotificationController
{
    private string $notificationResource;

    public function __construct(
        private NotificationServiceInterface $notificationService,
    ) {
        $this->notificationResource = config('data_transfer.'.NotificationResource::class);
    }

    public function show(Notification $notification): ApiResponse
    {
        return ApiResponse::make(
            data: $this->notificationResource::from($notification),
        );
    }

    public function read(Notification $notification, ReadNotificationRequest $request): ApiResponse
    {
        $readNotificationDto = config('data_transfer.'.ReadNotificationDto::class);

        $this->notificationService->read(
            id: $notification->id,
            data: $readNotificationDto::from($request)
        );

        return ApiResponse::noContent();
    }

    public function readMany(ReadManyNotificationRequest $request): ApiResponse
    {
        $readManyNotificationDto = config('data_transfer.'.ReadManyNotificationDto::class);

        $this->notificationService->readMany(
            data: $readManyNotificationDto::from($request)
        );

        return ApiResponse::noContent();
    }
}
