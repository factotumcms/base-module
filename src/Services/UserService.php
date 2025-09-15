<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Services\UserServiceInterface;
use Wave8\Factotum\Base\Dto\User\CreateUserDto;
use Wave8\Factotum\Base\Dto\User\UpdateUserDto;
use Wave8\Factotum\Base\Models\User;

class UserService implements UserServiceInterface
{
    /**
     * @throws \Exception
     */
    public function create(CreateUserDto|Data $data): Model
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

    public function getAll(): Collection
    {
        return User::all();
    }

    public function show(int $id): ?Model
    {
        try {

            return User::findOrFail($id);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(int $id, UpdateUserDto|Data $data): Model
    {
        try {

            $user = User::findOrFail($id);

            $user->update($data->toArray());

            return $user;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {

            $user = User::findOrFail($id);

            return $user->delete();

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
