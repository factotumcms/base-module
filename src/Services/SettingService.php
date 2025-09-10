<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\EntityService as EntityServiceContract;
use Wave8\Factotum\Base\Contracts\SettingService as SettingServiceContract;
use Wave8\Factotum\Base\Dto\SettingDto;
use Wave8\Factotum\Base\Models\Setting;
use Wave8\Factotum\Base\Types\BaseSettingGroup;
use Wave8\Factotum\Base\Types\SettingDataType;
use Wave8\Factotum\Base\Types\SettingType;

class SettingService implements EntityServiceContract, SettingServiceContract
{
    /**
     * @throws \Exception
     */
    public function create(SettingDto|Data $data): Setting
    {
        try {
            $setting = new Setting(
                attributes: $data->toArray()
            );

            $setting->save();

        } catch (\Exception $e) {
            throw $e;
        }

        return $setting;
    }

    /**
     * @throws \Exception
     */
    public function getSystemSettings(): Collection
    {
        try {

            return Cache::rememberForever('system_settings', function() {
                return Setting::where('type', SettingType::SYSTEM)->get();
            });

        } catch (\Exception $e) {
            throw $e;
        }

    }

    /**
     * @throws \Exception
     */
    public function getSystemSettingValue(string $key, string $group = BaseSettingGroup::MEDIA): mixed
    {
        $setting = $this->getSystemSettings()
            ->where('key', $key)
            ->where('group', $group)
            ->first();

        return $setting ? $this->castSettingValue(setting: $setting) : null;
    }

    private function castSettingValue(Setting $setting): mixed
    {
        return match ($setting->data_type) {
            SettingDataType::INTEGER => (int) $setting->value,
            SettingDataType::BOOLEAN => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            SettingDataType::FLOAT => (float) $setting->value,
            SettingDataType::JSON => json_decode($setting->value, true),
            SettingDataType::STRING => (string) $setting->value,
            default => $setting->value,
        };
    }
}
