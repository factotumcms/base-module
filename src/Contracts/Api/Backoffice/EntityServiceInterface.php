<?php

namespace Wave8\Factotum\Base\Contracts\Api\Backoffice;

use Illuminate\Contracts\Pagination\Paginator;
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

    public function filter(QueryFiltersDto $queryFilters): Paginator|LengthAwarePaginator;

    public function applyFilters(Builder $query, array $searchFilters): void;

    public function applySorting(Builder &$query, QueryFiltersDto $queryFilters): void;
}
