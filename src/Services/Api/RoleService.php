<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\RoleServiceInterface;
use Wave8\Factotum\Base\Contracts\FilterableInterface;
use Wave8\Factotum\Base\Contracts\SortableInterface;
use Wave8\Factotum\Base\Dtos\Api\Role\CreateRoleDto;
use Wave8\Factotum\Base\Dtos\Api\Role\UpdateRoleDto;
use Wave8\Factotum\Base\Models\Role;
use Wave8\Factotum\Base\Traits\Filterable;
use Wave8\Factotum\Base\Traits\Sortable;

class RoleService implements RoleServiceInterface, FilterableInterface, SortableInterface
{
    use Filterable;
    use Sortable;

    /**
     * Create a new role.
     */
    public function create(CreateRoleDto|Data $data): Model
    {
        return Role::create(
            attributes: $data->toArray()
        );
    }

    public function read(int $id): Model
    {
        return Role::findOrFail($id);
    }

    public function update(int $id, UpdateRoleDto|Data $data): Model
    {
        $role = Role::findOrFail($id);

        $role->update($data->toArray());

        return $role;
    }

    public function delete(int $id): void
    {
        $role = Role::findOrFail($id);

        $role->delete();
    }

    public function filter(): LengthAwarePaginator
    {
        // todo:: implement filtering and sorting
        $query = Role::query();

        //        $this->applyFilters($query, $queryFilters->search);
        //        $this->applySorting($query, $queryFilters);

        return $query->paginate(
            perPage: $queryFilters->perPage ?? 15,
            page: $queryFilters->page
        );
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
}
