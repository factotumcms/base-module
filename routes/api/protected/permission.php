<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\PermissionController;

Route::apiResource('permissions', PermissionController::class);
