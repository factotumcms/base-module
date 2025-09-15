<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\SettingController;

Route::resource('settings', SettingController::class);
