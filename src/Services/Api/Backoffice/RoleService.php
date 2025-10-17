<?php

namespace Wave8\Factotum\Base\Services\Api\Backoffice;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\Backoffice\RoleServiceInterface;
use Wave8\Factotum\Base\Contracts\FilterableInterface;
use Wave8\Factotum\Base\Contracts\SortableInterface;
use Wave8\Factotum\Base\Dtos\Api\Role\CreateRoleDto;
use Wave8\Factotum\Base\Dtos\Api\Role\UpdateRoleDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Enums\Permission\Permission;
use Wave8\Factotum\Base\Models\Role;

class RoleService implements FilterableInterface, RoleServiceInterface, SortableInterface
{
    /**
     * Create a new role.
     */
    public function create(CreateRoleDto|Data $data): Model
    {
        return Role::create(
            attributes: $data->toArray()
        );
    }

    /**
     * Retrieve a role by its ID.
     */
    public function show(int $id): ?Model
    {
        return Role::findOrFail($id);
    }

    /**
     * Update a role by its ID.
     */
    public function update(int $id, UpdateRoleDto|Data $data): Model
    {
        $role = Role::findOrFail($id);

        $role->update($data->toArray());

        return $role;
    }

    /**
     * Delete a role by its ID.
     */
    public function delete(int $id): bool
    {
        $role = Role::findOrFail($id);

        return $role->delete();
    }

    public function filter(QueryFiltersDto $queryFilters): Paginator|LengthAwarePaginator
    {
        $query = Role::query();

        $this->applyFilters($query, $queryFilters->search);
        $this->applySorting($query, $queryFilters);

        return $query->simplePaginate(
            perPage: $queryFilters->perPage ?? 15,
            page: $queryFilters->page
        );
    }

    /**
     * @param  Collection<Permission>  $permissions
     */
    public function assignPermissions(int $roleId, Collection $permissions): Model
    {
        /** @var Role $role */
        $role = Role::findOrFail($roleId);

        if ($permissions->isNotEmpty()) {
            $role->givePermissionTo($permissions);
        }

        return $role;
    }

    /**
     * Check if the role is a default system role.
     */
    public function isDefaultRole(int $roleId): bool
    {
        try {
            $role = Role::findOrFail($roleId)->name;
            $defaultRole = \Wave8\Factotum\Base\Enums\Role::tryFrom($role);

        } catch (\Exception $e) {
            return false;
        }

        return ! is_null($defaultRole);
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
