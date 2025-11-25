<?php

namespace Wave8\Factotum\Base\Builders;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Enums\FilterOperator;
use Spatie\QueryBuilder\QueryBuilder;
use Wave8\Factotum\Base\Enums\FilterType;

class UserQueryBuilder extends Builder
{
    public $filterable = [
        'first_name' => FilterType::LIKE,
        'last_name' => FilterType::LIKE,
        'username' => FilterType::LIKE,
        'email' => FilterType::LIKE,
        'is_active' => FilterType::EXACT,
    ];

    public $sortable = [
        'id',
        'first_name',
        'last_name',
        'username',
        'email',
        'is_active',
    ];

    public function filterByRequest()
    {
        $filters = (function () {
            $filters = [];
            foreach ($this->filterable as $field => $filterType) {
                match ($filterType) {
                    FilterType::EXACT => $filters[] = AllowedFilter::exact($field),
                    FilterType::DYNAMIC => $filters[] = AllowedFilter::operator($field, FilterOperator::DYNAMIC),
                    FilterType::LIKE => $filters[] = $field,
                };
            }

            return $filters;
        })();

        return QueryBuilder::for(get_class($this->model))->allowedFilters($filters)->allowedSorts($this->sortable);
    }
}
