<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Enums\Setting\Setting as SettingType;
use Wave8\Factotum\Base\Enums\Setting\SettingDataType;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Enums\Setting\SettingVisibility;
use Wave8\Factotum\Base\Models\Setting;

class SettingService implements SettingServiceInterface
{
    public const string CACHE_KEY_SYSTEM_SETTINGS = 'system_settings';

    public const string CACHE_KEY_USER_SETTINGS = 'user_settings_';

    public function __construct(public readonly Setting $setting) {}

    /**
     * Create a new setting.
     */
    public function create(Data $data): Model
    {
        $setting = new Setting(
            attributes: $data->toArray()
        );

        $setting->save();

        Cache::forget($this::CACHE_KEY_SYSTEM_SETTINGS);

        return $setting;
    }

    /**
     * Retrieve all system settings, cached indefinitely.
     */
    public function getSystemSettings(): Collection
    {
        return Cache::rememberForever($this::CACHE_KEY_SYSTEM_SETTINGS, function () {
            return Setting::where('visibility', SettingVisibility::SYSTEM)->get();
        });
    }

    /**
     * @throws \Exception
     */
    public function getSystemSettingValue(SettingType $key, SettingGroup $group): mixed
    {
        $setting = $this->getSystemSettings()
            ->where('key', $key)
            ->where('group', $group)
            ->first();

        return $setting ? $this->castSettingValue(setting: $setting) : null;
    }

    public function getUserSettings(int $userId): Collection
    {
        return Cache::rememberForever($this::CACHE_KEY_USER_SETTINGS.$userId, function () use ($userId) {
            $query = Setting::select(
                'settings.*',
                DB::raw('COALESCE(setting_user.value, settings.value) as user_value'),
            );

            $query->leftJoin('setting_user', function ($join) use ($userId) {
                $join->on('settings.id', '=', 'setting_user.setting_id')
                    ->where('setting_user.user_id', $userId);
            });

            $query->where('settings.visibility', SettingVisibility::USER);

            return $query->get()->each(function ($setting) {
                $setting->value = $setting->user_value ?? $setting->value;
                $setting->value = $this->castSettingValue(setting: $setting);
            });
        });
    }

    /**
     * Casts the setting value to its appropriate data type.
     */
    private function castSettingValue(Setting $setting): mixed
    {
        $data = match ($setting->data_type) {
            SettingDataType::INTEGER => (int) $setting->value,
            SettingDataType::BOOLEAN => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            SettingDataType::FLOAT => (float) $setting->value,
            SettingDataType::JSON => json_decode($setting->value, true),
            SettingDataType::STRING => (string) $setting->value,
            default => $setting->value,
        };

        return $data;
    }

    /**
     * Display the specified resource.
     */
    public function read(int $id): Model
    {
        return Setting::findOrFail($id);
    }

    /**
     * Update a setting value.
     */
    public function update(int $id, Data $data): Model
    {
        $setting = Setting::findOrFail($id);

        $setting->update($data->toArray());

        Cache::forget($this::CACHE_KEY_SYSTEM_SETTINGS);

        return $setting;
    }

    public function delete(int $id): void
    {
        // Todo:: To implement the delete logic or avoid
    }

    public function filter(): LengthAwarePaginator
    {
        $query = $this->setting->query()->filterByRequest();

        return $query->paginate();
    }
}
