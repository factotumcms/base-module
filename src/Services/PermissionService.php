<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Services\PermissionServiceInterface;
use Wave8\Factotum\Base\Dtos\Permission\CreatePermissionDto;
use Wave8\Factotum\Base\Dtos\Permission\UpdatePermissionDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Models\Permission;
use Wave8\Factotum\Base\Traits\Filterable;
use Wave8\Factotum\Base\Traits\Sortable;

class PermissionService implements PermissionServiceInterface
{
    use Sortable, Filterable;

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
            perPage: $queryFilters->per_page ?? 15,
            page: $queryFilters->page
        );
    }

}
