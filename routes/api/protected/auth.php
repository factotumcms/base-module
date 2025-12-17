<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
