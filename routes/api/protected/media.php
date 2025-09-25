<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\MediaController;
use Wave8\Factotum\Base\Models\Media;

Route::prefix('media')
    ->controller(MediaController::class)

    ->group(function () {
        Route::post('', 'create')->can('upload', Media::class);
        Route::get('{id}', 'show')->can('read', Media::class);
    });
