<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Api\NotificationServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Notification\ReadNotificationDto;
use Wave8\Factotum\Base\Http\Requests\Api\Notification\ReadNotificationRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Models\Notification;
use Wave8\Factotum\Base\Resources\Api\NotificationResource;

final readonly class NotificationController
{
    public function __construct(
        private NotificationServiceInterface $notificationService,
    ) {}

    public function show(Notification $notification): ApiResponse
    {
        return ApiResponse::make(
            data: NotificationResource::from($notification),
        );
    }

    public function read(Notification $notification, ReadNotificationRequest $request): ApiResponse
    {
        $this->notificationService->read(
            id: $notification->id,
            data: ReadNotificationDto::from(
                $request->all()
            )
        );

        return ApiResponse::HttpNoContent();
    }
}
