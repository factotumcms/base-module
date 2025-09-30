<?php

namespace Wave8\Factotum\Base\Contracts\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;

interface EntityServiceInterface
{
    public function create(Data $data): Model;
    public function show(int $id): ?Model;
    public function update(int $id, Data $data): Model;
    public function delete(int $id): bool;
    public function filter(QueryFiltersDto $queryFilters): LengthAwarePaginator;
    function applyFilters(Builder &$query, array $searchFilters):void;
}
