<?php

use Illuminate\Support\Facades\Route;
use Spatie\TranslationLoader\LanguageLine;
use Wave8\Factotum\Base\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::get('/test-language/{group}/{key}', function (string $group, string $key) {
    return __("{$group}.{$key}");

    return LanguageLine::getTranslationsForGroup('en', $group);
});




Route::post('/test-language/{group}/{key}/{value}', function (string $group, string $key, string $value) {
   /** @var $service \Wave8\Factotum\Base\Services\Api\LanguageService  */
    $service = app(\Wave8\Factotum\Base\Contracts\Api\LanguageServiceInterface::class);

   $service->create(
       new \Wave8\Factotum\Base\Dtos\Api\Language\RegisterLineDto(
           \Wave8\Factotum\Base\Enums\Locale::EN,
           $group,$key,$value
       )
   );


});
