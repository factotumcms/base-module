<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\RoleController;
use Wave8\Factotum\Base\Models\Role;

Route::prefix('roles')
    ->controller(RoleController::class)

    ->group(function () {
        Route::get('', 'index')->can('read', Role::class);
        Route::get('{id}', 'show')->can('read', Role::class);

        Route::post('', 'store')->can('create', Role::class);
        Route::put('{id}', 'update')->can('update', Role::class);
    });
