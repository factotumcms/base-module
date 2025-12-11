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
use Wave8\Factotum\Base\Models\Setting;

class SettingService implements SettingServiceInterface
{
    public const string SETTINGS_CACHE_KEY = 'settings';

    public const string USER_SETTINGS_CACHE_KEY = 'user_settings_';

    public function __construct(public readonly Setting $setting) {}

    public function getAll(): Collection
    {
        $query = $this->cachedSettings();

        return $query->each(function ($setting) {
            $setting->value = $setting->user_value ?? $setting->value;
            $setting->value = $this->castSettingValue(setting: $setting);
        });
    }

    public function getValue(SettingType $key, SettingGroup $group): mixed
    {
        $query = $this->cachedSettings();

        $filtered = $query->where('key', $key->value)
            ->where('group', $group->value);

        $filtered->each(function ($setting) {
            $setting->value = $setting->user_value ?? $setting->value;
            $setting->value = $this->castSettingValue(setting: $setting);
        });

        return $filtered->first()->value;
    }

    private function cachedSettings(): Collection
    {
        $userId = auth()?->user()?->id;
        $query = Setting::query();

        if (! $userId) {
            return Cache::rememberForever($this::SETTINGS_CACHE_KEY, function () use ($query) {
                return $query->get();
            });
        }

        return Cache::rememberForever($this::USER_SETTINGS_CACHE_KEY.$userId, function () use ($userId, $query) {
            $query->select(
                'settings.*',
                DB::raw('COALESCE(setting_user.value, settings.value) as user_value'),
            )->leftJoin('setting_user', function ($join) use ($userId) {
                $join->on('settings.id', '=', 'setting_user.setting_id')
                    ->where('setting_user.user_id', $userId);
            });

            return $query->get();
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

    public function create(Data $data): Model
    {
        $setting = new Setting(
            attributes: $data->toArray()
        );

        $setting->save();

        Cache::forget($this::SETTINGS_CACHE_KEY);

        return $setting;
    }

    public function read(int $id): Model
    {
        return Setting::findOrFail($id);
    }

    public function update(int $id, Data $data): Model
    {
        $setting = Setting::findOrFail($id);

        $setting->update($data->toArray());

        Cache::forget($this::SETTINGS_CACHE_KEY);

        return $setting;
    }

    public function filter(): LengthAwarePaginator
    {
        $query = $this->setting->query()->filterByRequest();

        return $query->paginate();
    }
}
