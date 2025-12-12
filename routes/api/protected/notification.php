<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\NotificationController;
use Wave8\Factotum\Base\Models\Notification;

Route::prefix('notifications')
    ->controller(NotificationController::class)
    ->group(function () {
        Route::get('', 'index')->can('read', Notification::class);
        Route::get('/{notification}', 'show')->can('view', 'notification');
        Route::patch('/{notification}/read', 'read')->can('mark-as-read', 'notification');
        Route::patch('/read', 'readMany')->can('mark-many-as-read', Notification::class);
    });
