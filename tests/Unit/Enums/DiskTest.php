<?php
describe('DiskEnum', function () {
    it('check DiskEnum has exact values', function () {

        $values = array_flip(\Wave8\Factotum\Base\Enums\Disk::getValues()->toArray());

        expect(array_keys($values))->toBe([
            'local',
            'public',
            's3',
        ]);

    });
});
