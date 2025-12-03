<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\UserController;
use Wave8\Factotum\Base\Models\Setting;
use Wave8\Factotum\Base\Models\User;

Route::prefix('users')
    ->controller(UserController::class)
    ->group(function () {
        Route::get('', 'index')->can('filter', User::class);
        Route::get('{user}', 'show')->can('read', 'user');
        Route::get('{id}/settings', 'settings')->can('read', Setting::class);

        Route::post('', 'store')->can('create', User::class);

        Route::put('{user}', 'update')->can('update', 'user');
        Route::put('{id}/settings/{setting}', 'updateSetting')->can('updateUserSetting', 'setting');

        Route::patch('{user}/change-password', 'changePassword')->can('changePassword', 'user');

        Route::delete('{user}', 'destroy')->can('delete', 'user');
    });
