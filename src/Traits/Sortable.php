<?php

namespace Wave8\Factotum\Base\Traits;

use Illuminate\Database\Eloquent\Builder;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;

trait Sortable
{
    public function applySorting(Builder &$query, QueryFiltersDto $queryFilters): void
    {
        if($queryFilters->sort_by) {
            $query = $query->orderBy($queryFilters->sort_by, $queryFilters->sort_order);
        }
    }
}
