<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\RoleServiceInterface;
use Wave8\Factotum\Base\Contracts\FilterableInterface;
use Wave8\Factotum\Base\Contracts\SortableInterface;
use Wave8\Factotum\Base\Models\Role;
use Wave8\Factotum\Base\Traits\Filterable;
use Wave8\Factotum\Base\Traits\Sortable;

class RoleService implements FilterableInterface, RoleServiceInterface, SortableInterface
{
    use Filterable;
    use Sortable;

    public function __construct(public readonly Role $role) {}

    /**
     * Create a new role.
     */
    public function create(Data $data): Model
    {
        return $this->role::create(
            attributes: $data->toArray()
        );
    }

    public function read(int $id): Model
    {
        return $this->role::findOrFail($id);
    }

    public function update(int $id, Data $data): Model
    {
        $role = $this->role::findOrFail($id);

        $role->update($data->toArray());

        return $role;
    }

    public function delete(int $id): void
    {
        $role = $this->role::findOrFail($id);

        $role->delete();
    }

    public function filter(): LengthAwarePaginator
    {
        // todo:: implement filtering and sorting
        $query = $this->role::query();

        //        $this->applyFilters($query, $queryFilters->search);
        //        $this->applySorting($query, $queryFilters);

        return $query->paginate();
    }

    /**
     * Check if the role is a default system role.
     */
    public function isDefaultRole(int $roleId): bool
    {
        try {
            $role = $this->role::findOrFail($roleId)->name;
            $defaultRole = \Wave8\Factotum\Base\Enums\Role::tryFrom($role);
        } catch (\Exception $e) {
            return false;
        }

        return ! is_null($defaultRole);
    }
}
