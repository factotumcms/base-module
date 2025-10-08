<?php

namespace Wave8\Factotum\Base\Services\Api\Backoffice;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\UserServiceInterface;
use Wave8\Factotum\Base\Contracts\FilterableInterface;
use Wave8\Factotum\Base\Contracts\SortableInterface;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\User\CreateUserDto;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\User\UpdateUserDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Models\User;

class UserService implements FilterableInterface, SortableInterface, UserServiceInterface
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

    /**
     * Retrieve a user by ID with its `profile_picture` relationship loaded.
     *
     * @param int $id The user's ID.
     * @return \Illuminate\Database\Eloquent\Model The requested User model with `profile_picture` loaded.
     * @throws \Exception If the user cannot be retrieved or another error occurs.
     */
    public function show(int $id): ?Model
    {
        try {

            return User::with('profile_picture')->findOrFail($id);

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

    public function applySorting(Builder $query, QueryFiltersDto $queryFilters): void
    {
        if ($queryFilters->sortBy) {
            $query->orderBy($queryFilters->sortBy, $queryFilters->sortOrder);
        }
    }

    public function applyFilters(Builder $query, ?array $searchFilters): void
    {
        foreach ($searchFilters as $field => $value) {

            $operator = substr($value, 0, 1);
            if (in_array($operator, ['<', '>'])) {

                $value = substr($value, 1);
                $query = $query->where($field, $operator, $value);

            } else {
                $query = $query->where($field, 'LIKE', "%$value%");
            }
        }
    }
}