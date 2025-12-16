<?php

namespace Wave8\Factotum\Base\Observers;

use Illuminate\Support\Facades\Cache;
use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Models\Setting;
use Wave8\Factotum\Base\Services\Api\SettingService;

class SettingObserver
{
    public function __construct(
        /** @var SettingService $settingService */
        private SettingServiceInterface $settingService,
    ) {}

    /**
     * Handle the Setting "created" event.
     *
     * @throws \Exception
     */
    public function created(Setting $setting): void
    {
        Cache::forget($this->settingService::SETTINGS_CACHE_KEY);
    }

    /**
     * Handle the Setting "updated" event.
     */
    public function updated(Setting $setting): void
    {
        Cache::forget($this->settingService::SETTINGS_CACHE_KEY);
    }

    /**
     * Handle the Setting "deleted" event.
     */
    public function deleted(Setting $setting): void
    {
        Cache::forget($this->settingService::SETTINGS_CACHE_KEY);
    }

    /**
     * Handle the Setting "restored" event.
     */
    public function restored(Setting $setting): void
    {
        //
    }

    /**
     * Handle the Setting "force deleted" event.
     */
    public function forceDeleted(Setting $setting): void
    {
        //
    }
}
