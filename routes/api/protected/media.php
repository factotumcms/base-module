<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\MediaController;

Route::post('media', [MediaController::class, 'create']);
Route::get('media/{id}', [MediaController::class, 'show']);
