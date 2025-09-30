<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\Backoffice\UserController;
use Wave8\Factotum\Base\Models\User;

Route::prefix('users')
    ->controller(UserController::class)
    ->group(function () {
        Route::get('', 'index')->can('read', User::class);
        Route::get('{id}', 'show')->can('read', User::class);
        Route::post('', 'store')->can('create', User::class);
        Route::put('{id}', 'update')->can('update', User::class);
    });
