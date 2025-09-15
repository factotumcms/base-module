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
     * @throws \Exception
     */
    public function create(CreateRoleDto|Data $data): Model
    {
        try {

            $role = Role::create(
                attributes: $data->toArray()
            );

        } catch (\Exception $e) {
            throw $e;
        }

        return $role;
    }

    public function getAll(): Collection
    {
        return Role::all();
    }

    public function show(int $id): ?Model
    {
        try {

            return Role::findOrFail($id);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(int $id, UpdateRoleDto|Data $data): Model
    {
        try {

            $role = Role::findOrFail($id);

            $role->update($data->toArray());

            return $role;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {

            $role = Role::findOrFail($id);

            return $role->delete();

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
