<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\PermissionServiceInterface;
use Wave8\Factotum\Base\Contracts\FilterableInterface;
use Wave8\Factotum\Base\Contracts\SortableInterface;
use Wave8\Factotum\Base\Dtos\Api\Permission\CreatePermissionDto;
use Wave8\Factotum\Base\Dtos\Api\Permission\UpdatePermissionDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Models\Permission;

class PermissionService implements FilterableInterface, PermissionServiceInterface, SortableInterface
{
    public function create(CreatePermissionDto|Data $data): Model
    {
        return Permission::create(
            attributes: $data->toArray()
        );
    }

    public function show(int $id): ?Model
    {
        return Permission::findOrFail($id);
    }

    /**
     * @throws \Exception
     */
    public function update(int $id, UpdatePermissionDto|Data $data): Model
    {
        $permission = Permission::findOrFail($id);

        $permission->update($data->toArray());

        return $permission;
    }

    public function delete(int $id): bool
    {
        $permission = Permission::findOrFail($id);

        return $permission->delete();
    }

    public function filter(QueryFiltersDto $queryFilters): Paginator|LengthAwarePaginator
    {
        $query = Permission::query();

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
            $query->orderBy($queryFilters->sortBy, $queryFilters->sortOrder->value);
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
