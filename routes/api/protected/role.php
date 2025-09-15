<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\RoleController;

Route::apiResource('roles', RoleController::class);
