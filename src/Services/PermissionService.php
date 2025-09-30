<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Services\PermissionServiceInterface;
use Wave8\Factotum\Base\Dtos\Permission\CreatePermissionDto;
use Wave8\Factotum\Base\Dtos\Permission\UpdatePermissionDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Models\Permission;

class PermissionService implements PermissionServiceInterface
{
    /**
     * Create a new permission
     */
    public function create(CreatePermissionDto|Data $data): Model
    {
        return Permission::create(
            attributes: $data->toArray()
        );
    }

    /**
     * Get a permission by id
     */
    public function show(int $id): ?Model
    {
        return Permission::findOrFail($id);
    }

    /**
     * Update a permission
     *
     * @throws \Exception
     */
    public function update(int $id, UpdatePermissionDto|Data $data): Model
    {
        $permission = Permission::findOrFail($id);

        $permission->update($data->toArray());

        return $permission;
    }

    /**
     * Delete a permission
     */
    public function delete(int $id): bool
    {
        $permission = Permission::findOrFail($id);

        return $permission->delete();
    }

    public function filter(QueryFiltersDto $queryFilters): LengthAwarePaginator
    {
        $query = Permission::query();

        $this->applyFilters($query, $queryFilters->search);
        $this->applySorting($query, $queryFilters);


        return $query->paginate($queryFilters->per_page ?? 15, ['*'], 'page', $queryFilters->page);
    }

    public function applyFilters(&$query, ?array $searchFilters):void
    {
        foreach ($searchFilters as $field => $value) {
            if (is_array($value)) {
                $query = $query->whereIn($field, $value);
            } else {
                $query = $query->where($field, $value);
            }
        }
    }

    public function applySorting(Builder &$query, QueryFiltersDto $queryFilters)
    {
        if($queryFilters->sort_by) {
            $query = $query->orderBy($queryFilters->sort_by, $queryFilters->sort_order);
        }
    }

}
