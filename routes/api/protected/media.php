<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\MediaController;

Route::post('media/upload', [MediaController::class, 'upload']);
Route::get('media/show-test', [MediaController::class, 'showTest']);
