<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\Mobile\AuthController;

Route::post('/login', [AuthController::class, 'login']);
