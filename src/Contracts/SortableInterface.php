<?php

namespace Wave8\Factotum\Base\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;

interface SortableInterface
{
    public function applySorting(Builder $query, QueryFiltersDto $queryFilters): void;
}
