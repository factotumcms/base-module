<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\UserController;
use Wave8\Factotum\Base\Http\Middleware\VerifyAuthToken;
use Wave8\Factotum\Base\Models\Setting;
use Wave8\Factotum\Base\Models\User;

Route::prefix('users')
    ->controller(UserController::class)
    ->group(function () {
        Route::get('', 'index')->can('filter', User::class);
        Route::get('{user}', 'show')->can('read', 'user');
        Route::get('{id}/settings', 'settings')->can('read', Setting::class);

        Route::post('', 'store')->can('create', User::class);

        Route::post('{user}', 'update')->can('update', 'user');
        Route::put('{id}/settings/{setting}', 'updateSetting')->can('updateUserSetting', 'setting');

        Route::patch('/change-password', 'changePassword')->can('changePassword', User::class)
            ->withoutMiddleware(VerifyAuthToken::class);

        Route::delete('{user}', 'destroy')->can('delete', 'user');
    });
