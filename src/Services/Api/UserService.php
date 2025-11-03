<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\UserServiceInterface;
use Wave8\Factotum\Base\Models\User;

class UserService implements UserServiceInterface
{
    public function __construct(public readonly User $user) {}

    public function create(Data $data): Model
    {
        return $this->user::create(
            attributes: $data->toArray()
        );
    }

    public function read(int $id): Model
    {
        return $this->user->findOrFail($id);
    }

    public function update(int $id, Data $data): Model
    {
        $user = $this->user::findOrFail($id);

        $user->update(
            attributes: $data->toArray()
        );

        return $user;
    }

    public function delete(int $id): void
    {
        $user = $this->user::findOrFail($id);

        $user->delete();
    }

    public function filter(): LengthAwarePaginator
    {
        $query = $this->user->query()
            ->filterByRequest();

        return $query->paginate();
    }
}
