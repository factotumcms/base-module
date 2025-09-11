<?php

use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Http\Controllers\Api\UserController;

 Route::get('/users', [UserController::class, 'all']);
 Route::get('/users/{id}', [UserController::class, 'read']);
Route::post('/users', [UserController::class, 'create']);

