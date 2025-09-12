<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Services\UserServiceInterface;
use Wave8\Factotum\Base\Dto\SettingDto;
use Wave8\Factotum\Base\Dto\UserDto;
use Wave8\Factotum\Base\Models\User;

class UserService implements UserServiceInterface
{
    /**
     * @throws \Exception
     */
    public function create(UserDto|Data $data): Model
    {
        try {

            $user = User::create(
                attributes: $data->toArray()
            );

        } catch (\Exception $e) {
            throw $e;
        }

        return $user;
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return User::all();
    }

    public function read(int $id): ?Model
    {
        return null;
    }

    public function update(int $id, SettingDto|Data $data): Model {}

    public function delete(int $id): bool
    {
        return true;
    }
}
