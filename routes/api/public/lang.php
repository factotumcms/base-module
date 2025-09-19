<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Wave8\Factotum\Base\Services\LanguageService;
use Wave8\Factotum\Base\Types\Locale;

Route::get('/testLocale', function (Request $request) {

    $service = app()->make(LanguageService::class);

    $service->updateByLocale(Locale::en_GB, 'auth', 'argomento', 'Test api inglese');

});
