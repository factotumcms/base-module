<?php

namespace Wave8\Factotum\Base\Traits;

use Illuminate\Database\Eloquent\Builder;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;

trait Filterable
{
    public function applyFilters(&$query, ?array $searchFilters):void
    {
        foreach ($searchFilters as $field => $value) {
            if (is_array($value)) {
                $query = $query->whereIn($field, $value);
            } else {
                $query = $query->where($field, $value);
            }
        }
    }
}
