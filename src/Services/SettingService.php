<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\EntityService as EntityServiceContract;
use Wave8\Factotum\Base\Contracts\SettingService as SettingServiceContract;
use Wave8\Factotum\Base\Dto\SettingDto;
use Wave8\Factotum\Base\Models\Setting;
use Wave8\Factotum\Base\Resources\SettingResource;
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
            $settings = Setting::where('type', SettingType::SYSTEM)->get()
                ->get()->map(function ($item) {
                    return SettingResource::from($item->toArray());
                });
        } catch (\Exception $e) {
            throw $e;
        }

        return $settings;
    }

    public function getSettingValue(string $key, string $type = SettingType::SYSTEM, string $group = BaseSettingGroup::MEDIA): mixed
    {
        $setting = Setting::where('key', $key)
            ->where('type', $type)
            ->where('group', $group)
            ->first();

        return $setting ? $this->castSettingValue($setting) : null;
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
