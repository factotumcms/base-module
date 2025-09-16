<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Services\RoleServiceInterface;
use Wave8\Factotum\Base\Dto\Role\CreateRoleDto;
use Wave8\Factotum\Base\Dto\Role\UpdateRoleDto;
use Wave8\Factotum\Base\Models\Role;

class RoleService implements RoleServiceInterface
{
    /**
     * Create a new role.
     * @param CreateRoleDto|Data $data
     * @return Model
     */
    public function create(CreateRoleDto|Data $data): Model
    {
        return Role::create(
            attributes: $data->toArray()
        );
    }

    /**
     * Retrieve all roles.
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Role::all();
    }

    /**
     * Retrieve a role by its ID.
     * @param int $id
     * @return Model|null
     */
    public function show(int $id): ?Model
    {
        return Role::findOrFail($id);
    }

    /**
     * Update a role by its ID.
     * @param int $id
     * @param UpdateRoleDto|Data $data
     * @return Model
     */
    public function update(int $id, UpdateRoleDto|Data $data): Model
    {
        $role = Role::findOrFail($id);

        $role->update($data->toArray());

        return $role;
    }

    /**
     * Delete a role by its ID.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $role = Role::findOrFail($id);

        return $role->delete();
    }
}
