<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\UserServiceInterface;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Traits\Filterable;
use Wave8\Factotum\Base\Traits\Sortable;

class UserService implements UserServiceInterface
{
    use Filterable;
    use Sortable;

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
}
