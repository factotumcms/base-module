<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\LanguageController;
use Wave8\Factotum\Base\Http\Controllers\Api\RoleController;
use Wave8\Factotum\Base\Models\Role;

Route::prefix('languages')
    ->controller(LanguageController::class)

    ->group(function () {
        Route::get('/{group}', 'index');

    });
