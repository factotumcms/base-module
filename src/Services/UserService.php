<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Support\Collection;
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
    public function create(UserDto|Data $data): User
    {
        try {
            $user = new User(
                attributes: $data->toArray()
            );

            $user->save();

        } catch (\Exception $e) {
            throw $e;
        }

        return $user;
    }

    public function getAll(): Collection
    {
        return User::all();
    }

    public function read(int $id): ?object
    {
        // TODO: Implement read() method.
    }

    public function update(int $id, SettingDto|Data $data): object
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id): bool
    {
        // TODO: Implement delete() method.
    }
}
