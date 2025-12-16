<?php

namespace Wave8\Factotum\Base\Contracts\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Enums\Setting\Setting as SettingType;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Models\Setting;

interface SettingServiceInterface
{
    public function getAll(): Collection;

    public function getValue(SettingType $key, SettingGroup $group): mixed;

    public function create(Data $data): Model;

    public function read(int $id): Model;

    public function update(Setting $setting, Data $data): Model;

    public function filter(): LengthAwarePaginator;
}
