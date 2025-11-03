<?php

namespace Wave8\Factotum\Base\Builders;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Enums\FilterOperator;
use Spatie\QueryBuilder\QueryBuilder;
use Wave8\Factotum\Base\Enums\FilterType;

class MediaQueryBuilder extends Builder
{
    public $filterable = [
        'name' => FilterType::LIKE,
        'type' => FilterType::LIKE,
        'file_name' => FilterType::LIKE,
        'mime_type' => FilterType::EXACT,
        'media_type' => FilterType::EXACT,
    ];

    public $sortable = [
        'id',
        'name',
        'file_name',
        'mime_type',
        'media_type',
        'size',
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

        return QueryBuilder::for(get_class($this->model))->allowedFilters($filters)
            ->allowedSorts($this->sortable);
    }
}
