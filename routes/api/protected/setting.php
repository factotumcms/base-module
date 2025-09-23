<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\SettingController;

Route::apiResource('settings', SettingController::class)->except(['store', 'destroy', 'update']);
