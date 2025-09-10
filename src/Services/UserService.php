<?php

namespace Wave8\Factotum\Base\Services;

use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\EntityService as EntityServiceContract;
use Wave8\Factotum\Base\Contracts\UserService as UserServiceContract;
use Wave8\Factotum\Base\Dto\UserDto;
use Wave8\Factotum\Base\Models\User;

class UserService implements EntityServiceContract, UserServiceContract
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
}
