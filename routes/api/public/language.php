<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\LanguageController;

Route::prefix('languages')
    ->controller(LanguageController::class)

    ->group(function () {
        Route::get('/{group}', 'index');
    });
