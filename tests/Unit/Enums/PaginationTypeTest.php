<?php

use Wave8\Factotum\Base\Enums\PaginationType;

describe('PaginationTypeEnum', function () {
    it('check PaginationTypeEnum has exact values', function () {
        $values = array_flip(PaginationType::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'standard',
            'simple',
            'cursor',
        ]);
    });
});
