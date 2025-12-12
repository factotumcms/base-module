<?php

namespace Wave8\Factotum\Base\Contracts\Api;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Models\User;

interface UserServiceInterface
{
    public function create(Data $data): Model;

    public function read(int $id): Model;

    public function update(User $user, Data $data): Model;

    public function updatePassword(User $user, string $password): User;

    public function delete(int $id): void;

    public function filter(): LengthAwarePaginator;

    public function updateSetting(int $id, int $settingId, Data $data): Model;

    public function getBy(string $column, string $value): Collection;
}
