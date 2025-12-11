<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\RoleServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\QueryPaginationDto;
use Wave8\Factotum\Base\Models\Role;

class RoleService implements RoleServiceInterface
{
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

    public function filter(QueryPaginationDto $paginationDto): LengthAwarePaginator
    {
        $query = $this->role->query()
            ->filterByRequest();

        return $query->paginate(
            perPage: $paginationDto->perPage ?? 15,
            page: $paginationDto->page ?? 1,
        );
    }

    /**
     * Check if the role is a default system role.
     */
    public function isDefaultRole(int $roleId): bool
    {
        $role = $this->role::findOrFail($roleId)->name;
        $defaultRole = \Wave8\Factotum\Base\Enums\Role::tryFrom($role);

        return ! is_null($defaultRole);
    }
}
