<?php

namespace Wave8\Factotum\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Enums\Locale;
use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Services\Api\SettingService;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var SettingService $settingService */
        $settingService = app(SettingServiceInterface::class);
        $requestLanguages = $request->getLanguages();

        if($request->user()) {
            if($locale = $settingService->getUserSettingValue(
                userId: $request->user()->id,
                key: Setting::LOCALE,
                group: SettingGroup::LOCALE
            )){
                app()->setLocale($locale);
                return $next($request);
            }
        }

        $availableLocales = $settingService->getSystemSettingValue(
            key: Setting::AVAILABLE_LOCALES,
            group: SettingGroup::LOCALE
        );


        foreach ($requestLanguages as $requestLanguage) {
            if(in_array($requestLanguage, $availableLocales)) {
                app()->setLocale($locale);
                return $next($request);
            }
        }

        app()->setLocale(config('app.fallback_locale'));

        return $next($request);
    }
}
