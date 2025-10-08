<?php

namespace Wave8\Factotum\Base\Services\Api\Mobile;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\Mobile\SettingServiceInterface;
use Wave8\Factotum\Base\Contracts\SortableInterface;
use Wave8\Factotum\Base\Dtos\Api\Mobile\Setting\CreateSettingDto;
use Wave8\Factotum\Base\Dtos\Api\Mobile\Setting\UpdateSettingDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Enums\Setting\Setting as SettingType;
use Wave8\Factotum\Base\Enums\Setting\SettingDataType;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Enums\Setting\SettingScope;
use Wave8\Factotum\Base\Models\Setting;

class SettingService implements SettingServiceInterface, SortableInterface
{
    public const string CACHE_KEY_SYSTEM_SETTINGS = 'system_settings';

    /**
     * Create a new setting.
     */
    public function create(CreateSettingDto|Data $data): Model
    {
        // todo:: To review the user's settings logic
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
            return Setting::where('scope', SettingScope::SYSTEM)->get();
        });
    }

    /**
     * @throws \Exception
     */
    public function getSystemSettingValue(SettingType $key, SettingGroup $group = SettingGroup::MEDIA): mixed
    {
        $setting = $this->getSystemSettings()
            ->where('key', $key)
            ->where('group', $group)
            ->first();

        return $setting ? $this->castSettingValue(setting: $setting) : null;
    }

    /**
     * Casts the setting value to its appropriate data type.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(int $id): ?Model
    {
        return Setting::findOrFail($id);
    }

    /**
     * Update a setting value.
     *
     * @param  Data  $data
     */
    public function update(int $id, UpdateSettingDto|Data $data): Model
    {
        $setting = Setting::findOrFail($id);

        $setting->update($data->toArray());

        Cache::forget($this::CACHE_KEY_SYSTEM_SETTINGS);

        return $setting;
    }

    public function delete(int $id): bool
    {
        // Todo:: To implement the delete logic or avoid
        return false;
    }

    /**
     * Retrieve all settings.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \Wave8\Factotum\Base\Models\Setting> Collection of all Setting models.
     */
    public function getAll(): Collection
    {
        return Setting::all();
    }

    /**
     * Get a paginated list of settings that match the provided query filters.
     *
     * Applies the filtering, sorting, and pagination parameters from the given
     * QueryFiltersDto to produce a paginated result set.
     *
     * @param QueryFiltersDto $queryFilters DTO containing filter, sort, and pagination options.
     * @return LengthAwarePaginator Paginated collection of Setting models matching the filters.
     */
    public function filter(QueryFiltersDto $queryFilters): LengthAwarePaginator
    {
        // TODO: Implement filter() method.
    }

    public function applySorting(Builder $query, QueryFiltersDto $queryFilters): void
    {
        if ($queryFilters->sortBy) {
            $query->orderBy($queryFilters->sortBy, $queryFilters->sortOrder);
        }
    }
}