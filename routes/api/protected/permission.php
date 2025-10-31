<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\PermissionController;
use Wave8\Factotum\Base\Models\Permission;

Route::prefix('permissions')
    ->controller(PermissionController::class)

    ->group(function () {
        Route::get('', 'index')->can('read', Permission::class);
        Route::get('{permission}', 'show')->can('read', 'permission');
    });
