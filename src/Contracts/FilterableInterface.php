<?php

namespace Wave8\Factotum\Base\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FilterableInterface
{
    public function applyFilters(Builder $query, ?array $searchFilters): void;
}
