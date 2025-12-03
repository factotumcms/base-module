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
        $user = $this->user::create(
            attributes: $data->toArray()
        );

        if ($data->password) {
            $user->password_histories()->create([
                'password' => $user->password,
                'expires_at' => now()->addDays(config('factotum_base.auth.password_expiration_days')),
            ]);
        }

        return $user;
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

    public function updatePassword(User $user, string $password): User
    {
        $user = $this->user::findOrFail($user->id);

        $user->password = $password;
        $user->save();

        $user->password_histories()->create([
            'password' => $user->password,
            'expires_at' => now()->addDays(config('factotum_base.auth.password_expiration_days')),
        ]);

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

    public function updateSetting(int $id, int $settingId, Data $data): Model
    {
        /** @var User $user */
        $user = $this->user::findOrFail($id);

        $user->settings()->sync([
            $settingId => ['value' => $data->value],
        ], false);

        return $user;
    }
}
