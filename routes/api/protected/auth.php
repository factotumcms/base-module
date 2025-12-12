<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\AuthController;

Route::post('/logout', [AuthController::class, 'logout']);
