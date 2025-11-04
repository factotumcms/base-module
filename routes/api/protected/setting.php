<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\SettingController;
use Wave8\Factotum\Base\Models\Setting;

Route::prefix('settings')
    ->controller(SettingController::class)
    ->group(function () {
        Route::get('', 'index')->can('read', Setting::class);
        Route::get('{setting}', 'show')->can('read', 'setting');
        Route::put('{setting}', 'update')->can('update', 'setting');
    });
