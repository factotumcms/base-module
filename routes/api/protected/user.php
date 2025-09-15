<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\UserController;

Route::apiResource('users', UserController::class);
