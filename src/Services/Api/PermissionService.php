<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\PermissionServiceInterface;
use Wave8\Factotum\Base\Models\Permission;

class PermissionService implements PermissionServiceInterface
{
    public function __construct(public readonly Permission $permission) {}

    public function create(Data $data): Model
    {
        return $this->permission::create(
            attributes: $data->toArray()
        );
    }

    public function read(int $id): Model
    {
        return $this->permission::findOrFail($id);
    }

    /**
     * @throws \Exception
     */
    public function update(int $id, Data $data): Model
    {
        $permission = $this->permission::findOrFail($id);

        $permission->update($data->toArray());

        return $permission;
    }

    public function delete(int $id): void
    {
        $permission = $this->permission::findOrFail($id);

        $permission->delete();
    }

    public function filter(): LengthAwarePaginator
    {
        $query = $this->permission->query()
            ->filterByRequest();

        return $query->paginate();
    }
}
