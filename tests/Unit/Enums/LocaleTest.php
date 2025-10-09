<?php
describe('LocaleEnum', function () {
    it('check LocaleEnum has exact values', function () {

        $values = array_flip(\Wave8\Factotum\Base\Enums\Locale::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'it',
            'en',
        ]);

    });
});
