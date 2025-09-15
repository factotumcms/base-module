<?php

namespace Wave8\Factotum\Base\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

interface EntityServiceInterface
{
    public function create(Data $data): Model;

    public function show(int $id): ?Model;

    public function update(int $id, Data $data): Model;

    public function delete(int $id): bool;

    public function getAll(): Collection;
}
