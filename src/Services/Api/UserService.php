<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\UserServiceInterface;
use Wave8\Factotum\Base\Contracts\FilterableInterface;
use Wave8\Factotum\Base\Contracts\SortableInterface;
use Wave8\Factotum\Base\Dtos\Api\User\CreateUserDto;
use Wave8\Factotum\Base\Dtos\Api\User\UpdateUserDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Traits\Filterable;
use Wave8\Factotum\Base\Traits\Sortable;

class UserService implements FilterableInterface, SortableInterface, UserServiceInterface
{
    use Filterable, Sortable;

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

    /**
     * Retrieve a user by ID with its `avatar` relationship loaded.
     *
     * @param  int  $id  The user's ID.
     * @return Model|null The requested User model with 'avatar' loaded.</code>
     *
     * @throws \Exception If the user cannot be retrieved or another error occurs.
     */
    public function show(int $id): ?Model
    {
        try {

            return User::with('avatar')->findOrFail($id);

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

    public function filter(QueryFiltersDto $queryFilters): Paginator|LengthAwarePaginator
    {
        $query = User::query();

        $this->applyFilters($query, $queryFilters->search);
        $this->applySorting($query, $queryFilters);

        return $query->simplePaginate(
            perPage: $queryFilters->perPage ?? 15,
            page: $queryFilters->page
        );
    }
}
