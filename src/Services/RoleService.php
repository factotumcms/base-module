<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Services\RoleServiceInterface;
use Wave8\Factotum\Base\Dto\Role\CreateRoleDto;
use Wave8\Factotum\Base\Dto\Role\UpdateRoleDto;
use Wave8\Factotum\Base\Models\Role;
use Wave8\Factotum\Base\Enum\Permission;

class RoleService implements RoleServiceInterface
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
     * Retrieve all roles.
     */
    public function getAll(): Collection
    {
        return Role::all();
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

    public function filter(array $filters): Collection
    {
        // todo:: tipizzare i filters con un dto
        $query = Role::query();

        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get();
    }

    /**
     * @param  \Illuminate\Support\Collection<Permission>  $permissions
     */
    public function assignPermissions(int $roleId, \Illuminate\Support\Collection $permissions): Model
    {
        /** @var Role $role */
        $role = Role::findOrFail($roleId);

        if ($permissions->isNotEmpty()) {
            $role->givePermissionTo($permissions);
        }

        return $role;
    }
}
