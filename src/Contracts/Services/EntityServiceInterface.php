<?php

namespace Wave8\Factotum\Base\Contracts\Services;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

interface EntityServiceInterface
{
    public function create(Data $data): object;

    public function read(int $id): ?object;

    public function update(int $id, Data $data): object;

    public function delete(int $id): bool;

    public function getAll(): Collection;
}
