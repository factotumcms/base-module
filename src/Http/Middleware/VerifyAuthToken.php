<?php

namespace Wave8\Factotum\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Services\Api\SettingService;

class VerifyAuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ! $request->user()->tokenCan('*')) {
            return ApiResponse::unauthorized(__('passwords.expired'));
        }

        //  Update token expiration date on each request
        /** @var SettingService $settingService */
        $settingService = app(SettingServiceInterface::class);

        $tokenExpirationDays = $settingService->getValue(
            key: Setting::AUTH_TOKEN_EXPIRATION_DAYS,
            group: SettingGroup::AUTH,
        );

        $token = $request->user()->currentAccessToken();
        $token->update([
            'expires_at' => now()->addDays($tokenExpirationDays),
        ]);

        return $next($request);
    }
}
