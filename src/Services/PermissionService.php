<?php

namespace Wave8\Factotum\Base\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Services\PermissionServiceInterface;
use Wave8\Factotum\Base\Dtos\Permission\CreatePermissionDto;
use Wave8\Factotum\Base\Dtos\Permission\UpdatePermissionDto;
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
     * Get all permissions
     */
    public function getAll(): Collection
    {
        return Permission::all();
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

    public function filter(array $filters): Collection
    {
        // TODO: Implement filter() method.
    }
}
