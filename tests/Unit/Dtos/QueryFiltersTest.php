<?php

use Wave8\Factotum\Base\Dtos\QueryFiltersDto;

describe('QueryFiltersDto', function () {
    it('successfully create a new QueryFiltersDto instance', function () {

        $dto = new QueryFiltersDto(
            page: 1, perPage: 15, sortBy: 'id', sortOrder: 'asc', search: []
        );

        expect($dto)->toBeInstanceOf(QueryFiltersDto::class);
    });

});
