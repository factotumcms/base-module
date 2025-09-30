<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\Backoffice\PermissionController;
use Wave8\Factotum\Base\Models\Permission;

Route::prefix('permissions')
    ->controller(PermissionController::class)

    ->group(function () {
        Route::get('', 'index')->can('read', Permission::class);
        Route::get('{id}', 'show')->can('read', Permission::class);
    });
