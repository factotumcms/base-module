<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Services\LanguageService;
use Wave8\Factotum\Base\Types\LocaleType;

Route::get('/testLocale', function (Request $request) {

    $service = app()->make(LanguageService::class);

    $service->updateByLocale(LocaleType::en_GB, 'auth', 'argomento', 'Test api inglese');

});
