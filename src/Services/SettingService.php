<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Services\SettingServiceInterface;
use Wave8\Factotum\Base\Dto\Setting\CreateSettingDto;
use Wave8\Factotum\Base\Models\Setting;
use Wave8\Factotum\Base\Types\SettingDataType;
use Wave8\Factotum\Base\Types\SettingGroupType;
use Wave8\Factotum\Base\Types\SettingType;
use Wave8\Factotum\Base\Types\SettingTypeType;

class SettingService implements SettingServiceInterface
{
    public const string CACHE_KEY_SYSTEM_SETTINGS = 'system_settings';

    /**
     * @throws \Exception
     */
    public function create(CreateSettingDto|Data $data): Model
    {
        $setting = new Setting(
            attributes: $data->toArray()
        );

        $setting->save();

        Cache::forget($this::CACHE_KEY_SYSTEM_SETTINGS);

        return $setting;
    }

    /**
     * @throws \Exception
     */
    public function getSystemSettings(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::rememberForever($this::CACHE_KEY_SYSTEM_SETTINGS, function () {
            return Setting::where('type', SettingTypeType::SYSTEM)->get();
        });
    }

    /**
     * @throws \Exception
     */
    public function getSystemSettingValue(SettingType $key, SettingGroupType $group = SettingGroupType::MEDIA): mixed
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

    public function show(int $id): ?Model
    {
        return Setting::findOrFail($id);
    }

    public function update(int $id, Data $data): Model
    {
        $setting = Setting::findOrFail($id);

        $setting->update($data->toArray());

        Cache::forget($this::CACHE_KEY_SYSTEM_SETTINGS);

        return $setting;
    }

    public function delete(int $id): bool
    {
        return false;
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Setting::all();
    }
}
