<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Spatie\TranslationLoader\LanguageLine;
use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Services\Api\SettingService;

final readonly class LanguageController
{
    private string $roleResource;

    public function __construct(

    ) {

    }

    public function index(string $group): ApiResponse
    {
        /** @var SettingService $settingService */
        $settingService = app(SettingServiceInterface::class);

        Gate::denyIf(
            condition: function(?User $user) use ($group, $settingService) {
                $publicGroups = $settingService->getSystemSettingValue(
                    key: Setting::PUBLIC_LANGUAGE_GROUPS,
                    group: SettingGroup::LOCALE
                );

                return !in_array($group, $publicGroups ?? []);
        });

        return ApiResponse::make(
            data: LanguageLine::getTranslationsForGroup(locale: config('app.locale'), group: $group)
        );
    }

}
