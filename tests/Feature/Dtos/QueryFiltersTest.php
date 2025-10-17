<?php

use Wave8\Factotum\Base\Dtos\QueryFiltersDto;

describe('QueryFiltersDto', function () {
    it('successfully create a new QueryFiltersDto instance', function () {

        $dto = new QueryFiltersDto(
            search: []
        );

        $dto->page = 1;
        $dto->perPage = 15;
        $dto->sortBy = 'id';
        $dto->sortOrder = \Ramsey\Collection\Sort::Descending;

        expect($dto)->toBeInstanceOf(QueryFiltersDto::class);
    });

});
