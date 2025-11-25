<?php

use Illuminate\Support\Facades\Route;
use Spatie\TranslationLoader\LanguageLine;
use Wave8\Factotum\Base\Contracts\Api\LanguageServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Language\RegisterLineDto;
use Wave8\Factotum\Base\Enums\Locale;
use Wave8\Factotum\Base\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
