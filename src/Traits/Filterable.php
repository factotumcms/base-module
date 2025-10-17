<?php

namespace Wave8\Factotum\Base\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function applyFilters(Builder $query, ?array $searchFilters): void
    {
        foreach ($searchFilters as $field => $value) {

            $operator = substr($value, 0, 1);
            if (in_array($operator, ['<', '>'])) {

                $value = substr($value, 1);
                $query = $query->where($field, $operator, $value);

            } else {
                $query = $query->where($field, 'LIKE', "%$value%");
            }
        }
    }
}
